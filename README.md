## Configuration

**Make Dir**
```
mkdir packages/Tikweb
```
**Clone Package**
```
cd packages/Tikweb
git clone https://arhafijur@bitbucket.org/tikweb/tik-cms-api.git 
```


**Update Composer.json** 

```json
"autoload": {
  "psr-4": {
    "Tikweb\\TikCmsApi\\": "packages/Tikweb/tik-cms-api/src"
  }
}
```
**Add Provider**

```php
Tikweb\TikCmsApi\Providers\BaseReviewServiceProvider::class
```
```
composer dump-autoload
```

**Migrate db**
```
php artisan migrate:refresh --path=packages/Tikweb/tik-cms-api/src/Database/Migrations/2020_12_23_071537_tik_cms_setup_tables_v1.php
php artisan migrate:refresh --path=packages/Tikweb/tik-cms-api/src/Database/Migrations/2021_01_12_155326_tik_cms_setup_tables_v2.php
php artisan migrate:refresh --path=packages/Tikweb/tik-cms-api/src/Database/Migrations/2021_02_03_164236_tik_cms_setup_tables_v3.php
php artisan migrate:refresh --path=packages/Tikweb/tik-cms-api/src/Database/Migrations/2021_02_23_125115_tik_cms_setup_tables_v4.php
```
**Seeding**
```
php artisan db:seed --class=Tikweb\TikCmsApi\Database\Seeders\LanguageTableSeeder
```
