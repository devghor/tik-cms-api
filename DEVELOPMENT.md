## Configuration

**Make Dir**
```
mkdir packages/Devghor
```
**Clone Package**
```
cd packages/Devghor
git clonehttps://github.com/devghor/tik-cms-api.git 
```


**Update Composer.json**

```json
"autoload": {
  "psr-4": {
    "Devghor\\TikCmsApi\\": "packages/Devghor/tik-cms-api/src"
  }
}
```
**Add Provider**

```php
Devghor\TikCmsApi\Providers\BaseReviewServiceProvider::class
```
```
composer dump-autoload
```
