# VoyagerDataTransport
A command line tools to generate the Controller and the View files 
which can import data to database and export data to the file which extension included excel, csv and pdf
<br>
Notice that the package is based on https://voyager.devdojo.com/
<br>
## Before install
You must confirm that the voyager package installed before
<br>
How to install the voyager package:
```php
composer require tcg/voyager
```
## How to install the VoyagerDataTransport
```php
composer require vanchao0519/voyager-data-transport
```
## How to use
```php
php artisan voyager:data:transport <data-tabel-name>
```