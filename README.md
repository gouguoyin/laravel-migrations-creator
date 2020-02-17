### 项目说明

Laravel Migrations Creator 是一个专门为Laravel6+ 版本从已有数据表反向生成数据库迁移文件的扩展包，支持字段、索引和外键，目前仅支持Mysql驱动。

### 安装说明

#### 1、安装 migration-creator

```bash
composer require --dev "gouguoyin/laravel-migrations-creator"
```

#### 2. 添加到服务提供者

在 `config/app.php` 的 `providers` 数组中加入

```bash
Gouguoyin\MigrationsCreator\MigrateServiceProvider::class
```

#### 3. 开发环境运行

如果你只在开发环境中安装，那么可以在 `app/Providers/AppServiceProvider.php` 的 `register` 方法中写入下面代码：

```bash
public function register()
{
    if ($this->app->environment() !== 'production') {
        $this->app->register(\Gouguoyin\MigrationsCreator\MigrateServiceProvider::class);
    }
    // ...
}
```

## 使用说明

#### 生成默认数据库的所有数据表迁移文件

`php artisan migrate:create`

#### 生成指定数据库的所有数据表迁移文件

`php artisan migrate:create --connection='mysql''`

#### 生成指定数据表迁移文件

`php artisan migrate:create 'table1,table2,……'`

或者

`php artisan migrate:create --tables='table1,table2,……'`

#### 生成忽略数据表之外的数据表迁移文件

`php artisan migrate:create --ignores='table1,table2,……'`

## 更新日志

Changelog for Laravel Migrations Generator

### 2020-02-17
* 支持表注释
* 支持日期时间字段CURRENT_TIMESTAMP以及on update CURRENT_TIMESTAMP

## 致谢声明

感谢Xethron 的 [migrations-generator](https://github.com/Xethron/migrations-generator) 扩展，本扩展借鉴了此扩展的思路，遗憾的是此扩展已经停止更新，仅支持到 Laravel5.4 版本
