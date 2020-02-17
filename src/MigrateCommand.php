<?php

namespace Gouguoyin\MigrationsCreator;

use Gouguoyin\MigrationsCreator\Creators\Schema;
use Gouguoyin\MigrationsCreator\Drivers\Mysql\SchemaCreator;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MigrateCommand extends Command
{
    protected $signature = 'migrate:make {tables?} {--tables=} {--ignores=} {--connection=}';

    protected $description = '从已有数据表创建迁移文件';

    protected $schema;

    public function handle()
    {
        foreach ($this->getTables() as $table) {

            if($this->getSchemaCreator()->hasTable($table)){
                File::put($this->getFilePath($table), $this->getFileContent($table));
                $this->info('Created:' . $this->getFilePath($table));
            }else{
                $this->error( "The table $table does not exist");
            }

        }
    }

    /**
     * 获取所有数据表
     * @return array
     */
    protected function getTables()
    {
        if ($this->argument('tables')) {
            $tables = explode(',', $this->argument('tables'));
        } elseif ($this->option('tables')) {
            $tables = explode(',', $this->option('tables'));
        } else {
            $tables = $this->getSchemaCreator()->getTables();
        }

        $ignores = explode(',', $this->option('ignores'));

        return array_diff($tables, $ignores);
    }

    /**
     * 获取指定迁移文件路径
     * @param string $table
     * @return string
     */
    protected function getFilePath($table)
    {
        return database_path('migrations') . DIRECTORY_SEPARATOR . Carbon::now()->format('Y_m_d_His') . '_create_' . $table . '_table.php';
    }

    /**
     * 获取指定表的迁移文件内容
     * @param string$table
     * @return string
     */
    protected function getFileContent($table)
    {
        return <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class {$this->getClassName($table)} extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        {$this->getSchemaCreator()->getCreateStatements($table)}
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('{$table}');
    }
}
PHP;
    }

    /**
     * 获取迁移类名
     * @return string
     */
    protected function getClassName($table)
    {
        return 'Create' . ucwords(Str::camel($table)) . 'Table';
    }

    protected function getSchemaCreator()
    {
        $connection = $this->option('connection') ?? config('database.default');
        return new SchemaCreator($connection);
    }

}