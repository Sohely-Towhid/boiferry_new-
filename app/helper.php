<?php
/**
 * Add Active Class in NAV
 * @param  [type] $items [description]
 * @return [type]        [description]
 */
function nav_active($items)
{
    return array_map(function ($item) {
        return ($item['link'] == '/') ? preg_quote('/', '/') : preg_quote($item['link'], '/') . '.*';
    }, $items);
}

/**
 * Show if Role
 * @param  array   $roles [description]
 * @param  boolean $user  [description]
 * @return [type]         [description]
 */
function show_if($roles = [], $user = false)
{
    if (isset($roles[0]) && $roles[0] == '*') {return true;}
    $user = ($user) ? $user : Auth::user();
    return in_array(@$user->role, $roles);
}

/**
 * Show Nav Active
 * @param  [type]  $items       [description]
 * @param  boolean $current_url [description]
 * @return [type]               [description]
 */
function nav_active_show($items, $current_url = false)
{
    if (!$current_url) {
        $current_url = request()->path();
    }
    $regex = (is_array($items)) ? "/" . implode("|", $items) . "/i" : '/' . $items . '$/i';
    return (preg_match($regex, $current_url)) ? " active menu-item-active menu-item-open " : '';
}

/**
 * English Number to Bangla Number
 * @return [type] [description]
 */
function e2b($number)
{
    if (app()->getLocale() == 'en') {
        return $number;
    }
    $en = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
    $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    return str_replace($en, $bn, $number);
}

/**
 * Bangla Number to English Number
 * @return [type] [description]
 */
function b2e($number, $force = false)
{
    if ($force == false & app()->getLocale() == 'bn') {
        return $number;
    }
    $en = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
    $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    return str_replace($bn, $en, $number);
}

function bnMonth($number)
{
    $number = (int) $number;
    $en     = ['', 1, 2, 3, 4, 5, 6, 7, 8, 9];
    $bn     = ['', 'জানুয়ারি', 'ফেব্রুয়ারী ', 'মার্চ ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগষ্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'];
    return str_replace($en, $bn, $number);
}

/**
 * Show Many Size Image
 * @param  [type]  $path [description]
 * @param  boolean $size [description]
 * @return [type]        [description]
 */
function showImage($path, $size = false)
{
    $path = 'assets/images/' . $path;
    if ($size) {
        return asset(str_replace("redactor/", "redactor/{$size}_", $path));
    }
    return asset($path);
}

/**
 * Load Setting From Database
 * @param  boolean $type    [description]
 * @param  boolean $nocache [description]
 * @return [type]           [description]
 */
function loadSetting($type = false, $nocache = false)
{
    if (!$type) {
        $type = 'book';
    }
    $setting = cache($type . '_setting');
    if ($nocache) {
        $setting = false;
    }
    if (!$setting) {
        /*$setting = $items = App\Models\Setting::where('name', 'like', $type . '%')->get()->keyBy(function ($item) {
        // $setting = $items = DB::table('settings')->where('name', 'like', $type . '%')->get()->keyBy(function ($item) {
        return $item->name;
        });*/
        $setting = DB::table('settings')->where('name', 'like', $type . '%')->get()->mapWithKeys(function ($item) {
            $item->value = json_decode($item->value);
            return [$item->name => $item];
        });
        Cache::forever($type . '_setting', $setting);
    }
    return $setting;
}

/**
 * Make Fa Star
 * @param  [type] $value [description]
 * @return [type]        [description]
 */
function htmlStar($value, $size = '', $class = 'ml-3')
{
    $html = '<div class="text-yellow-darker ' . $class . '">';
    for ($i = 1; $i <= $value; $i++) {
        $html .= '<small class="fas fa-star ' . $size . '"></small>';
    }
    $html .= '</div>';
    return $html;
}

/**
 * Make EAN-8 Barcode
 * @param  [type] $code [description]
 * @return [type]       [description]
 */
function ean8($code)
{
    $code           = strval($code);
    $sum1           = $code[1] + $code[3] + $code[5];
    $sum2           = 3 * ($code[0] + $code[2] + $code[4] + $code[6]);
    $checksum_value = $sum1 + $sum2;
    $checksum_digit = 10 - ($checksum_value % 10);
    if ($checksum_digit == 10) {
        $checksum_digit = 0;
    }
    return $code . $checksum_digit;
}

/**
 * Book Fair
 * @param  [type] $year [description]
 * @return [type]       [description]
 */
function bookFair($year = false)
{
    if (!$year) {$year = date('Y');}
    $items = App\Models\Book::where('status', 1)->whereMonth('published_at', '02')->whereYear('published_at', $year);
    return $items->paginate();
}

/**
 * canonical url
 */
if (!function_exists('canonical_url')) {
    function canonical_url()
    {
        if (\Illuminate\Support\Str::startsWith($current = url()->current(), 'https://www')) {
            return str_replace('https://www.', 'https://', $current);
        }
        return str_replace('https://www.', 'https://', $current);
    }
}

/**
 * Basic Trnslate
 * @param  [type] $string [description]
 * @return [type]         [description]
 */
function basicTrns($string)
{
    $en = ['hardcover', 'paperback'];
    $bn = ['হার্ডকভার', 'পেপারব্যাক'];
    return str_replace($en, $bn, $string);
}

function _percent($rate, $sale, $extra = '')
{
    if ($rate > $sale) {
        $percent = e2b(ceil(100 - ((100 / $rate) * $sale))) . $extra;
    } else {
        $percent = 0;
    }
    return $percent;
}
