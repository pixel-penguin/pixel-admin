# Pixel Admin Form Pacakge

## If you have difficulty using this package, please follow the tutorial at: 
https://www.udemy.com/course/crash-course-for-laravel-7-and-vuejs-basics/?referralCode=F7C3DE21AB2B71FBD59F

### Add
```
use Illuminate\Support\Facades\Schema;

public function boot()
{
    Schema::defaultStringLength(191);
}
```
### In AppServiceProvider.php

### Then in cmd prompt
```
composer require laravel/ui
php artisan ui vue --auth
```

### Remember to do

```
npm install
```

### For npm installs:

```
npm install @mdi/font --save
npm install simple-modal-vue --save
npm install apexcharts --save
npm install vue-apexcharts --save
npm install vue-multiselect --save
npm install vue-tables-2 --save
npm install vue-vanilla-datetime-picker --save
npm install vue-clipboard2 --save
npm install sortablejs --save
npm install @voerro/vue-tagsinput --save
npm install @neos21/bootstrap3-glyphicons --save
npm install @riophae/vue-treeselect --save
npm install vuejs-datepicker --save
npm install vue-sweetalert2 --save
npm install v-tooltip --save
npm install @tinymce/tinymce-vue --save
npm install font-awesome --save
npm install vue-nestable --save
npm install font-awesome--save
npm install pretty-checkbox --save
npm install vue-axios --save
npm install pretty-checkbox-vue --save
npm install hooper --save
npm install --save vue-swatches
```

### For composer includes do:

```
composer require pixel-penguin/pixel-admin
composer require jrm2k6/cloudder
composer require doctrine/dbal
composer require spatie/laravel-analytics
composer require laravel/helpers
```
### After this: remember to run:
```
php artisan migrate
```

### In you  wepback.mix.js insert:

```
mix.js('vendor/pixel-penguin/pixel-admin/src/assets/js/admin.js', 'public/js')
   .sass('vendor/pixel-penguin/pixel-admin/src/assets/sass/admin.scss', 'public/css');

mix.styles([
        'vendor/pixel-penguin/pixel-admin/src/assets/themeincludes/style.css',
    ], 'public/css/admin-main.css');

mix.scripts([
        // vendor
        'vendor/pixel-penguin/pixel-admin/src/assets/themeincludes/jquery.slimscroll.js',
        'vendor/pixel-penguin/pixel-admin/src/assets/themeincludes/app.js'

    ], 'public/js/admin-main.js');
```

In your .env file, these are required

```

CLOUDINARY_API_KEY=
CLOUDINARY_API_SECRET=
CLOUDINARY_CLOUD_NAME=

ANALYTICS_VIEW_ID=

CLOUDINARY_BASE_FOLDER_PATH=
MIX_CLOUDINARY_CLOUD_NAME=
MIX_APP_URL=

MIX_CATEGORY_MAX_LEVEL=3

LOGO_IMAGE_NAME=
SPECIALS_BACKGROUND_IMAGE_NAME=

NAVIGATION_BORDER_COLOR="#d9991a"
NAVIGATION_TOP_TEXT_COLOR="#000"
NAVIGATION_BOTTOM_TEXT_COLOR="#000"

WEBSITE_INVERTING_COLOR_1="#FFF"

WEBSITE_COLOR_1="#FFA63F"
WEBSITE_COLOR_2="#FF5200"
WEBSITE_COLOR_3=""
WEBSITE_COLOR_4=""


WEBSITE_DETAIL_ADDRESS="Swakopmund, Namibia"
WEBSITE_DETAIL_PHONE=
WEBSITE_DETAIL_EMAIL=
WEBSITE_DETAIL_WORKING_HOURS=

MIX_WEBSITE_DETAIL_PHONE=
MIX_WEBSITE_DETAIL_EMAIL=

WEBSITE_DETAIL_NEWSLETTER_TEXT=

INVOICE_NOTICE=
EMAIL_ADDRESS_ORDER_1=
EMAIL_ADDRESS_ORDER_2=
EMAIL_SEND_USER_EMAIL=1
```

## JSON requests:

### Pages Routes

#### Search Products
```
Method: Post
Params: search
Path: /search/products
```
#### Get list of Pages  in tree form
```
Method: Get
Params: unpublished ['Y, 'N', or blank for N]
Path: /pages/{unpublished?}
```
#### Get page detail
```
Method: Get
Params: pageId
Path: page/detail/{pageId}
```

#### Get page gallery
```
Method: Get
Params: pageId
Path: page/gallery/{pageId}
```

## Using Facade
### insert wherever you need to use facade
```
use PixelPenguinAdmin;
```
#### Get all pages
````
PixelPenguinAdmin::getAllPages()
