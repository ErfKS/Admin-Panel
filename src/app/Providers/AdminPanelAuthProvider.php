<?php
namespace erfan_kateb_saber\admin_panel\app\Providers;

use erfan_kateb_saber\admin_panel\app\Console\Commands\ChangeAdmin;
use erfan_kateb_saber\admin_panel\app\Extensions\Xml\XML_Manager;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;

class AdminPanelAuthProvider extends ServiceProvider
{
    public function boot()
    {

        Config::set('auth.guards.admin_panel', [
            'driver' => 'session',
            'provider' => 'admin_panel',
        ]);
        Config::set('auth.providers.admin_panel', [
            'driver' => 'admin_panel_driver',
        ]);

        /*
         * 'private' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
        ],
        */

        Config::set('filesystems.disks.private',[
            'driver' => 'local',
            'root' => storage_path('app/private'),
        ]);

        Auth::provider('admin_panel_driver', function ($app, array $config) {
            return new AdminUserProvider();
        });



        $this->loadRoutesFrom(__DIR__.'/../../routes.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/view/main','main');
        $this->loadViewsFrom(__DIR__.'/../../resources/view/layout','layout');
        $this->loadViewsFrom(__DIR__.'/../../resources/view/components','component');

        Blade::component('component::form.checkbox', 'form-checkbox');
        Blade::component('component::form.button', 'form-button');
        Blade::component('component::form.dropdown', 'form-dropdown');
        Blade::component('component::form.hidden', 'form-hidden');
//        Blade::component('package-alert', listbox::class);
        Blade::component('component::form.number', 'form-number');
        Blade::component('component::form.text', 'form-text');

        $this->publishes([
            __DIR__.'/../../public' => public_path('vendor/admin_panel'),
        ], 'laravel-assets');


        if(XML_Manager::xmlToArray('admin_panel/admin_users.xml', ['numberRowId']) !== null) {
            $data = [
                "numberRowId1" => [
                    'username' => 'admin',
                    'password' => Hash::make('admin')
                ]
            ];
            XML_Manager::arrayToXml($data, '/admin_panel/admin_users.xml', '<admin_users/>');
        }

        $admins = XML_Manager::xmlToArray('admin_panel/admin_users.xml',['numberRowId'])[1];
        AboutCommand::add('Admin Panel', fn () => [
            'Version' => '1.0.0',
            'Username' => $admins['username'],
            'Password' => $admins['password']
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                ChangeAdmin::class
            ]);
        }
    }

    public function register()
    {

    }


}
