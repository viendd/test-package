<?php

namespace App\Modules;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        // Đăng ký modules theo cấu trúc thư mục
        $directories = array_map('basename', File::directories(__DIR__));
        foreach ($directories as $moduleName) {
            $this->registerModule($moduleName);
        }
    }

    // Khai báo đăng ký cho từng modules
    private function registerModule($moduleName) {

        // Khai báo configs
//        $configFile = [
//            $moduleName => __DIR__.'/'.$moduleName.'/config.php',
//        ];
//        foreach ($configFile as $alias => $path) {
//            $this->mergeConfigFrom($path, $alias);
//        }

        $modulePath = __DIR__ .'/'.$moduleName.'/';
        // Khai báo thành phần ở đây
        // Khai báo route
        if (File::exists($modulePath . "Routes/routes.php")) {
            $this->loadRoutesFrom($modulePath . "Routes/routes.php");
        }

        // Khai báo migration
        // Toàn bộ file migration của modules sẽ tự động được load
        if (File::exists($modulePath . "Migrations")) {
            $this->loadMigrationsFrom($modulePath . "Migrations");
        }
        // Khai báo views
        // Gọi view thì ta sử dụng: view('Demo::index'), @extends('Demo::index'), @include('Demo::index')
        if (File::exists($modulePath . "Resources/views")) {
            $this->loadViewsFrom($modulePath . "Resources/views", $moduleName);
        }

        // Khai báo helpers
        if (File::exists($modulePath . "Helpers")) {
            // Tất cả files có tại thư mục helpers
            $helper_dir = File::allFiles($modulePath . "Helpers");
            // khai báo helpers
            foreach ($helper_dir as $key => $value) {
                $file = $value->getPathName();
                require $file;
            }
        }

        // Khai báo languages
        if (File::exists($modulePath . "Resources/lang")) {
            // Đa ngôn ngữ theo file php
            // Dùng đa ngôn ngữ tại file php resources/lang/en/general.php : @lang('Demo::general.hello')
            $this->loadTranslationsFrom($modulePath . "Resources/lang", $moduleName);
            // Đa ngôn ngữ theo file json
            $this->loadJSONTranslationsFrom($modulePath . 'Resources/lang');
        }
    }
}
