<?php
use Illuminate\Support\Facades\Route;

// $domain = str_replace(['http://', 'https://', '/'], '', config('app.url', 'boiferry.com'));
$domain = str_replace(['http://', 'https://', '/', 'www.', 'management.', 'seller.'], '', request()->getHttpHost());
$domain = ($domain) ? $domain : 'boiferry.com';
$domain = (preg_match("/boiferry|book/", $domain)) ? $domain : 'boiferry.com';

Auth::routes();
Route::get('auth/{provider}', 'App\Http\Controllers\Auth\SocialiteController@redirect');
Route::get('auth/{provider}/callback', 'App\Http\Controllers\Auth\SocialiteController@Callback');

// Route::get('test', [App\Http\Controllers\WebBookController::class, 'fbEvent']);

Route::get('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);
Route::get('locale/{locale}', [App\Http\Controllers\WebController::class, 'locale']);
Route::get('fb-feed', [App\Http\Controllers\WebController::class, 'fbFeed']);
Route::any('callback/bkash', [App\Http\Controllers\InvoiceController::class, 'paymentBkash']);
Route::any('callback/nagad', [App\Http\Controllers\InvoiceController::class, 'paymentNagad'])->name('nagad.callback');

/**
 * Seller Center
 */
$seller = function () {
    Route::group(['middleware' => ['auth', 'role:vendor']], function () {
        Route::get('/', [App\Http\Controllers\SellerController::class, 'index'])->name('seller_home');
        Route::get('/search', [App\Http\Controllers\SellerController::class, 'search']);
        Route::get('/setting', [App\Http\Controllers\SellerController::class, 'setting']);
        Route::post('/setting', [App\Http\Controllers\SellerController::class, 'SaveSetting']);
        Route::post('/redactor-image', [App\Http\Controllers\RedactorController::class, 'redactorImage']);
        Route::resource('book', App\Http\Controllers\BookController::class);
        Route::resource('product', App\Http\Controllers\ProductController::class);
        Route::resource('invoice', App\Http\Controllers\VendorInvoiceController::class);

        // Select 2
        Route::any('author/select', [App\Http\Controllers\AuthorController::class, 'select']);
        Route::any('publication/select', [App\Http\Controllers\PublicationController::class, 'select']);
        Route::any('book/category/select', [App\Http\Controllers\CategoryController::class, 'select']);
        Route::any('product/category/select', [App\Http\Controllers\ProductCategoryController::class, 'select']);
        Route::any('brand/select', [App\Http\Controllers\ProductBrandController::class, 'select']);
    });
};

Route::domain('seller.' . $domain)->name('seller.')->group($seller);

/**
 * Management
 */
$admin = function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index']);
    Route::get('/profile', [App\Http\Controllers\AdminController::class, 'profile']);
    Route::post('/profile', [App\Http\Controllers\AdminController::class, 'saveProfile']);
    Route::get('/search', [App\Http\Controllers\AdminController::class, 'search']);

    Route::get('backup', [App\Http\Controllers\AdminController::class, 'backup'])->middleware('role:admin');
    Route::get('marketing', [App\Http\Controllers\AdminController::class, 'marketing'])->middleware('role:admin|marketing');
    Route::post('/redactor-image', [App\Http\Controllers\RedactorController::class, 'redactorImage']);
    Route::get('/redactor-image-list', [App\Http\Controllers\RedactorController::class, 'redactorImageList']);
    Route::resource('slider', App\Http\Controllers\SliderController::class)->middleware('role:admin|product-manager');

    Route::get('/setting/books', [App\Http\Controllers\SettingController::class, 'booksSetting'])->middleware('role:admin|product-manager');
    Route::post('/setting/books', [App\Http\Controllers\SettingController::class, 'postbooksSetting'])->middleware('role:admin|product-manager');
    Route::get('/setting/feature', [App\Http\Controllers\SettingController::class, 'booksFeature'])->middleware('role:admin|product-manager');
    Route::post('/setting/feature', [App\Http\Controllers\SettingController::class, 'postbooksFeature'])->middleware('role:admin|product-manager');

    Route::get('/setting/subscription', [App\Http\Controllers\SettingController::class, 'booksSubscription'])->middleware('role:admin|product-manager');
    Route::post('/setting/subscription', [App\Http\Controllers\SettingController::class, 'postbooksSubscription'])->middleware('role:admin|product-manager');

    Route::any('user/select', [App\Http\Controllers\UserController::class, 'select'])->middleware(['role:admin|manager|product-manager|key-account-manager|crm']);

    Route::resource('user', App\Http\Controllers\UserController::class)->middleware(['role:admin|manager|crm']);
    Route::resource('coupon', App\Http\Controllers\CouponController::class)->middleware(['role:admin|manager|product-manager']);
    Route::resource('review', App\Http\Controllers\ReviewController::class)->middleware(['role:admin|manager|key-account-manager|product-manager|marketing']);
    Route::resource('subject', App\Http\Controllers\SubjectController::class)->middleware(['role:admin|manager|product-manager']);
    Route::post('author/merge', [App\Http\Controllers\AuthorController::class, 'authorMerge'])->middleware(['role:admin|manager|product-manager']);
    Route::any('author/select', [App\Http\Controllers\AuthorController::class, 'select'])->middleware(['role:admin|manager|product-manager']);
    Route::resource('author', App\Http\Controllers\AuthorController::class)->middleware(['role:admin|manager|product-manager']);
    Route::any('publication/select', [App\Http\Controllers\PublicationController::class, 'select'])->middleware(['role:admin|manager|product-manager|marketing']);
    Route::resource('publication', App\Http\Controllers\PublicationController::class)->middleware(['role:admin|manager|product-manager|marketing']);
    Route::any('book/category/select', [App\Http\Controllers\CategoryController::class, 'select'])->middleware(['role:admin|manager|key-account-manager|product-manager']);
    Route::resource('book/category', App\Http\Controllers\CategoryController::class)->middleware(['role:admin|manager|key-account-manager|marketing|product-manager']);
    Route::any('vendor/select', [App\Http\Controllers\VendorController::class, 'select'])->middleware(['role:admin|manager|key-account-manager|product-manager']);
    Route::get('seller/{id}/download', [App\Http\Controllers\VendorController::class, 'download'])->middleware(['role:admin|manager|key-account-manager|product-manager']);
    Route::resource('seller', App\Http\Controllers\VendorController::class)->middleware(['role:admin|manager|key-account-manager|product-manager']);
    Route::any('book/select', [App\Http\Controllers\BookController::class, 'select'])->middleware(['role:admin|manager|key-account-manager|product-manager']);
    Route::get('book/bulk-price', [App\Http\Controllers\BookController::class, 'bulkPrice']);
    Route::post('book/bulk-price', [App\Http\Controllers\BookController::class, 'postBulkPrice']);

    Route::get('book/requisition', [App\Http\Controllers\BookController::class, 'requisition']);
    Route::post('book/requisition', [App\Http\Controllers\BookController::class, 'requisitionPost']);

    Route::resource('book', App\Http\Controllers\BookController::class);

    Route::any('product/category/select', [App\Http\Controllers\ProductCategoryController::class, 'select']);

    Route::resource('payout', App\Http\Controllers\PayoutController::class, ['except' => ['store', 'edit', 'delete']])->middleware(['role:admin|manager|accounts|key-account-manager']);
    Route::resource('blog', App\Http\Controllers\BlogController::class)->middleware(['role:admin|manager']);
    Route::resource('page', App\Http\Controllers\PageController::class)->middleware(['role:admin|manager']);

    Route::put('address/{id}', [App\Http\Controllers\AddressController::class, 'update']);
    Route::get('invoice/{id}/print', [App\Http\Controllers\InvoiceController::class, 'print']);
    Route::post('invoice/shipped', [App\Http\Controllers\InvoiceController::class, 'shipped']);
    Route::get('invoice/sign', [App\Http\Controllers\InvoiceController::class, 'signQZ']);

    Route::resource('invoice', App\Http\Controllers\InvoiceController::class);
    Route::any('report/{name}', [App\Http\Controllers\ReportController::class, 'reportProcess'])->middleware(['role:admin|manager|marketing|accounts']);
    Route::resource('qa', App\Http\Controllers\QuestionController::class);
    Route::resource('product/category', App\Http\Controllers\ProductCategoryController::class)->names('management.pc.');
    Route::any('product/category/select', [App\Http\Controllers\ProductCategoryController::class, 'select']);
    Route::resource('product', App\Http\Controllers\ProductController::class)->middleware(['role:admin|manager|key-account-manager']);
    Route::resource('subscription', App\Http\Controllers\SubscriptionController::class)->middleware(['role:admin|manager|product-manager|crm']);
    Route::resource('popup', App\Http\Controllers\PopupController::class)->middleware(['role:admin']);
};

Route::domain('management.' . $domain)->name('management.')->middleware(['auth', 'role:admin|accounts|product-manager|key-account-manager|marketing|crm|logistics'])->group($admin);

$bookSite = function () {
    Route::get('/', [App\Http\Controllers\WebBookController::class, 'index'])->name('book_home');
    Route::get('/ajax-search', [App\Http\Controllers\WebBookController::class, 'ajaxSearch']);
    Route::get('/ajax/category', [App\Http\Controllers\WebBookController::class, 'ajaxCategory']);
    Route::get('/ajax/author', [App\Http\Controllers\WebBookController::class, 'ajaxAuthor']);
    Route::get('/search', [App\Http\Controllers\WebBookController::class, 'search']);

    Route::get('/category', [App\Http\Controllers\WebBookController::class, 'categories']);
    Route::get('/category/{slug}', [App\Http\Controllers\WebBookController::class, 'category']);
    Route::get('/pre-order', [App\Http\Controllers\WebBookController::class, 'books']);

    Route::get('/authors', [App\Http\Controllers\WebBookController::class, 'authors']);
    Route::get('/author/{slug}', [App\Http\Controllers\WebBookController::class, 'author']);

    Route::get('/publication', [App\Http\Controllers\WebBookController::class, 'publishers']);
    Route::get('/publisher/{slug}', [App\Http\Controllers\WebBookController::class, 'publisher']);
    Route::get('/bestseller', [App\Http\Controllers\WebBookController::class, 'bestseller']);
    Route::get('/boimela', [App\Http\Controllers\WebBookController::class, 'bookfair']);
    Route::get('/boimela/{year}', [App\Http\Controllers\WebBookController::class, 'bookfair']);
    Route::get('/books', [App\Http\Controllers\WebBookController::class, 'books']);
    Route::get('/book/{slug}', [App\Http\Controllers\WebBookController::class, 'bookSingle']);
    Route::post('/book/{slug}', [App\Http\Controllers\WebBookController::class, 'bookSinglePost'])->middleware('auth');
    Route::any('/ajax-cart', [App\Http\Controllers\WebController::class, 'ajaxCart']);
    Route::any('/ajax-cart-list', [App\Http\Controllers\WebController::class, 'ajaxCartList']);
    Route::get('/cart', [App\Http\Controllers\WebController::class, 'cart']);
    Route::get('/checkout', [App\Http\Controllers\WebController::class, 'checkout']);
    Route::post('/checkout', [App\Http\Controllers\WebController::class, 'PostCheckout']);
    Route::get('/become-a-seller', [App\Http\Controllers\WebController::class, 'seller']);
    Route::post('/become-a-seller', [App\Http\Controllers\WebController::class, 'sellerPost']);
    Route::get('/legal/{slug}', [App\Http\Controllers\WebBookController::class, 'showLegal']);
    Route::post('/support', [App\Http\Controllers\WebBookController::class, 'postSupport']);
};

Route::domain($domain)->group($bookSite);
Route::domain('www.' . $domain)->name('www.')->group($bookSite);

$mainSite = function () {
    Route::get('/', [App\Http\Controllers\WebController::class, 'index']);
    Route::get('/ajax-serch', [App\Http\Controllers\WebController::class, 'ajaxSearch']);
    Route::get('/search', [App\Http\Controllers\WebController::class, 'search']);
    Route::get('/product', [App\Http\Controllers\ProductController::class, 'webIndex']);
    Route::get('/product/{slug}', [App\Http\Controllers\ProductController::class, 'show']);

    /**
     * Other Pages
     */
    Route::get('/blog', [App\Http\Controllers\BlogController::class, 'webIndex']);
    Route::get('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'webShow']);

    /**
     * Checkout + Cart & Legal
     */
    Route::any('/ajax-cart', [App\Http\Controllers\WebController::class, 'ajaxCart']);
    Route::get('/cart', [App\Http\Controllers\WebController::class, 'cart']);
    Route::get('/checkout', [App\Http\Controllers\WebController::class, 'checkout']);

};

Route::group(['prefix' => 'my-account', 'middleware' => ['auth']], function () {
    Route::get('/', [App\Http\Controllers\AccountController::class, 'dashboard'])->name('home');
    Route::get('/orders', [App\Http\Controllers\AccountController::class, 'order']);
    Route::get('/order/{id}', [App\Http\Controllers\AccountController::class, 'orderShow']);
    Route::post('/order/{id}', [App\Http\Controllers\AccountController::class, 'orderPost'])->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    Route::get('/profile', [App\Http\Controllers\AccountController::class, 'profile']);
    Route::post('/profile', [App\Http\Controllers\AccountController::class, 'postProfile']);
    Route::get('/wishlist', [App\Http\Controllers\AccountController::class, 'wishlist']);
    Route::get('/support', [App\Http\Controllers\AccountController::class, 'support']);
    Route::get('/subscription', [App\Http\Controllers\AccountController::class, 'subscription']);
    Route::post('/subscription', [App\Http\Controllers\AccountController::class, 'subscriptionPost'])->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
});

/**
 * All Fallback
 */
Route::fallback([App\Http\Controllers\PageController::class, 'showOther']);
