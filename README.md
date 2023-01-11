Install Package
```
composer require intervention/image spatie/laravel-sitemap spatie/laravel-backup spatie/laravel-menu laravel/ui yajra/laravel-datatables-oracle yajra/laravel-datatables-html mews/purifier
composer require barryvdh/laravel-debugbar --dev
php artisan ui bootstrap --auth
```
php artisan scout:import "App\Models\Books"

### Model
`php artisan make:_model User mcr`

### Menu
```
Menu::new()
    ->add(Link::to('/', 'Home'))
    ->submenu('More', Menu::new()
        ->addClass('submenu')
        ->link('/about', 'About')
        ->link('/contact', 'Contact')
    );
```

web.php

```php
Route::post('/redactor-image', 'RedactorController@redactorImage');
Route::get('/redactor-image-list', 'RedactorController@redactorImageList');
```

In `app\Http\Kernel.php` add

```php
'cors' => \App\Http\Middleware\CORS::class,
```

## How to use
```php
<x-form::input column="4" name="abc" title="Saiful" :required="true" step="0.1" pattern="0.1" type="text" value="{{ $item->title }}" />
<x-form::image column="4" name="abc" title="Saiful" :required="true" value="" />
<x-form::images column="4" name="abc" title="Saiful" :required="true" value="" />
<x-form::textarea column="4" name="textarea" title="textarea" :required="true" value="{{ $item->title }}" />
<x-form::rta column="4" name="textarea1" title="textarea" :required="true" value="{{ $item->details }}" />
<x-form::select column="4" name="post" title="Saiful" :required="true" step="0.1" pattern="0.1" :options="$options" select-title="title" select-value="id" placeholder="Select Option" value="{{ $item->id }}"/>
<x-form::checkbox column="4" name="chk" title="Chk Test" :required="true" value="yes" />
```

https://github.com/mewebstudio/purifier

### Select2
```js
function user_template(data) {
    if (data.loading) {return data.text;}
    var markup = '' + 
    "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__avatar'>" + data.name + "</div>" +
        "<div class='select2-result-repository__meta'>" +
            "<div class='select2-result-repository__title'>Mobile: " + data.mobile + "</div>"+
            "<div class='select2-result-repository__title'>Email: " + data.email +"</div>"+
        "</div>" + 
    "</div>";
  return markup;
}

function formatResult (data) {
    return data.name || data.text;
}

var user_select = $("#ref_id").select2({
    ajax: {
        delay: 200,
        minimumInputLength: 2,
        url: '{{ url('/admin/user/select') }}',
        processResults: function(data) {
            setTimeout(function() { $('.select2-results__option').trigger("mouseup"); }, 100);
            return {
                results: data.results
            };
        },
        cache: false
    },
    // escapeMarkup: function (markup) { return markup; },
    // templateResult: user_template,
    templateSelection: formatResult
});

function select2_search($el, term) {
    $el.select2('open');
    var $search = $el.data('select2').dropdown.$search || $el.data('select2').selection.$search;
    $search.val(term);
    $search.trigger('keyup');
}

function select2_search($el, term) {
    if(Array.isArray(term)){
        $.each(term, function(i,v){
            setTimeout(function(){
                select2_search($el, 'id:' + v);
            }, i * 600);
        });
    }else{
        $el.select2('open');
        var $search = $el.data('select2').dropdown.$search || $el.data('select2').selection.$search;
        $search.val(term);
        $search.trigger('keyup');
        setTimeout(function() { $('.select2-results__option').trigger("mouseup"); }, 500);
    }
}

select2_search(user_select, 'id:{{ $item->ref_id }}');
```


Best Seller
- Book
- Book By Year
- Book By Month
- Book By Week
- Author
- Author By Year
- Author By Month
- Author By Week



[program:bf-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /home/boiferry/web/boiferry.com/public_html/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=boiferry
numprocs=2
redirect_stderr=true
stdout_logfile=/home/boiferry/web/boiferry.com/public_html/storage/logs/worker.log
stopwaitsecs=3600


supervisorctl status
sudo supervisorctl start bf-worker:*
supervisorctl stop all


php artisan horizon:terminate
sudo supervisorctl start horizon
sudo supervisorctl stop horizon


pdf to epub https://toepub.com/