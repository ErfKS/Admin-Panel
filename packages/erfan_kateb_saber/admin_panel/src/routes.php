<?php
use Illuminate\Support\Facades\Route;

Route::prefix("admin_panel")->middleware('web')->namespace('erfan_kateb_saber\admin_panel\app\Controllers')->group(function () {

    Route::get('loginPage', 'Login@index')->name('admin_panel.loginPage');
    Route::post('login', 'Login@doLogin')->name('admin_panel.doLogin');



    Route::middleware('auth:admin_panel')->group(function () {
        Route::get('/', 'admin_panelController@index')->name('admin_panel.index');
        Route::get("getList/{part}/{list}", 'admin_panelController@getList')->name('admin_panel.getList');
        Route::post("setStatus/{path}", 'admin_panelController@editStatus')->name('admin_panel.setStatus');
        Route::get("fresh/{freshMode}", 'admin_panelController@freshDatabase')->name('admin_panel.freshDatabase');
        Route::get("update", 'admin_panelController@updateDatabase')->name('admin_panel.updateDatabase');
        Route::get("logout", 'Login@doLogout')->name('admin_panel.Logout');
        Route::post("editTotalValue", 'admin_panelController@editTotalValue')->name('admin_panel.editTotalValue');
        Route::post("addManualPathRoute", 'admin_panelController@addManualPathRoute')->name('admin_panel.addManualPathRoute');
        Route::post("addManualPrefixRoute", 'admin_panelController@addManualPrefixRoute')->name('admin_panel.addManualPrefixRoute');
        Route::post("dropRecord", 'admin_panelController@dropRecord')->name('admin_panel.dropRecord');

        Route::prefix('ManageDatabase')->group(function(){
            Route::post("createNewTable", 'DatabaseManagerController@createNewTable')->name('admin_panel.createNewTable');
        });

    });
});
