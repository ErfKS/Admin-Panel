<p align="center">
<a href="https://img.shields.io/packagist/dt/erfan_kateb_saber/admin_panel"><img src="https://img.shields.io/packagist/dt/erfan_kateb_saber/admin_panel" alt="Total Downloads"></a>
<a href="https://img.shields.io/packagist/dt/erfan_kateb_saber/admin_panel"><img src="https://img.shields.io/packagist/v/erfan_kateb_saber/admin_panel" alt="Latest Stable Version"></a>
<a href="https://img.shields.io/packagist/dt/erfan_kateb_saber/admin_panel"><img src="https://img.shields.io/packagist/l/erfan_kateb_saber/admin_panel" alt="License"></a>
</p>

# Admin-Panel
Admin Panel is a package for laravel that can manage Route access and tables of database

## Install
To install this package, run this command:
```
composer require erfan_kateb_saber/admin_panel
```
Then you can login in your admin panel in this route:
```url
YOUR_URL/admin_panel/loginPage
```
This username and password is `admin`.

To change the admin password, run this command:
```
php artisan admin:change -p ADMIN_PASSWORD
```
The Admin username and password is saved in `YOUR_PROJECT\storage\app\private\admin_panel\db_admin_users.xml` and the route access database saved in `YOUR_PROJECT\storage\app\private\admin_panel\db_route_access.xml`.

## Manage route access
### Auto Routes
To disable access routes, uncheck the status routes and then click `Save All` button.

### Auto Prefix Routes
To disable access prefix of routes, uncheck the status prefix and then click `Save All` button.

### Manual Routes
To add access route manually, in the `Create New` write the path route and set status on/off and then click insert.

### Manual Prefix Routes
To add access prefix manually, in the `Create New` write the path route and set status on/off and then click insert.

## Fresh Routes
### From Navbar
Deletes all status of Auto Routes and Auto Prefix Routes, but keep your Manual data (Manual Routes and Manual Prefix Routes).

### From <a href='#manual-routes'>Manual Routes</a> and <a href='#manual-prefix-routes'>Manual Prefix Routes</a>
Deletes all status of Auto Routes and Auto Prefix Routes and **not keep** your Manual data (Manual Routes and Manual Prefix Routes).

## Optimize XML Database
Removes unnecessary rows from XML Database.

## License
The Admin Panel package is open-sourced licensed under the MIT license.
