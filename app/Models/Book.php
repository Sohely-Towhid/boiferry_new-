<?php

namespace App\Models;

use Cache;
use Carbon\Carbon;
use DB;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Book extends Model
{
    use HasFactory;
    use Searchable;
    use Cachable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vendor_id',
        'language',
        'isbn',
        'title',
        'title_bn',
        'slug',
        'author_id',
        'author',
        'author_bn',
        'images',
        'preview',
        'ebook',
        'audio',
        'publisher_id',
        'category_id',
        'published_at',
        'rate',
        'sale',
        'number_of_page',
        'stock',
        'point',
        'shelf',
        'rating_review',
        'status',
        'pre_order',
        'type',
        'short_description',
        'description',
        'seo',
        'others',
        'vpc',
        'buy',
        'actual_stock',
        'ebook_rate',
        'ebook_sale',
        'subscription',
        'free_page',
        'ebook_type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['buy', 'shelf', 'vpc'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'rating_review' => 'object',
        'images'        => 'array',
        'seo'           => 'object',
        'others'        => 'object',
    ];

    /**
     * Get the indexable data array for the model.
     * https://stackoverflow.com/questions/46004552/laravel-scout-only-search-in-specific-fields
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = [
            'id'             => $this->id,
            'isbn'           => $this->isbn,
            'title'          => $this->title,
            'title_bn'       => $this->title_bn,
            'author'         => $this->author,
            'author_bn'      => $this->author_bn,
            'published_year' => $this->published_year,
            'seo'            => $this->seo,
            'others'         => $this->others,
            'publication'    => $this->publication->name,
            'publication_bn' => $this->publication->name_bn,
        ];

        return $array;
    }

    /**
     * Modify the query used to retrieve models when making all of the models searchable.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function makeAllSearchableUsing($query)
    {
        return $query->with('publication');
    }

    /**
     * Determine if the model should be searchable.
     *
     * @return bool
     */
    public function shouldBeSearchable()
    {
        return ($this->status == 1) ? true : false;
    }

    /**
     * Get the author record associated with the record.
     */
    public function _author()
    {
        return $this->hasOne('App\Models\Author', 'id', 'author_id');
    }

    /**
     * Get the category record associated with the record.
     */
    public function category()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }

    /**
     * Get the publication record associated with the record.
     */
    public function publication()
    {
        return $this->hasOne('App\Models\Publication', 'id', 'publisher_id');
    }

    /**
     * Get SEO Title
     *
     * @return string
     */
    public function getSeoTitleAttribute()
    {
        return $this->author_bn . " এর " . $this->title_bn;
    }

    /**
     * Get SEO Brand
     *
     * @return string
     */
    public function getSeoBrandAttribute()
    {
        return $this->publication->name_bn;
    }

    /**
     * Custom Date Apply
     * @param  [type] $query  [description]
     * @param  [type] $filter [description]
     * @param  [type] $column [description]
     * @return [type]         [description]
     */
    public function dateFilter($query, $filter, $column)
    {
        if ($filter == 'day') {
            return $query->whereDate($column, date('Y-m-d'));
        } elseif ($filter == 'week') {
            $now       = Carbon::now();
            $StartDate = $now->startOfWeek()->format('Y-m-d 00:00:00');
            $EndDate   = $now->endOfWeek()->format('Y-m-d 23:59:59');
            return $query->whereBetween($column, [$StartDate, $EndDate]);
        } elseif ($filter == 'last_7') {
            $now       = Carbon::now();
            $EndDate   = $now->format('Y-m-d 23:59:59');
            $StartDate = $now->subDays(7)->format('Y-m-d 00:00:00');
            return $query->whereBetween($column, [$StartDate, $EndDate]);
        } elseif ($filter == 'month') {
            $now       = Carbon::now();
            $StartDate = date('Y-m-01 00:00:00');
            $EndDate   = date('Y-m-t 23:59:59');
            return $query->whereBetween($column, [$StartDate, $EndDate]);
        } elseif ($filter == 'last_30') {
            $now       = Carbon::now();
            $EndDate   = $now->format('Y-m-d 23:59:59');
            $StartDate = $now->subDays(30)->format('Y-m-d 00:00:00');
            return $query->whereBetween($column, [$StartDate, $EndDate]);
        } elseif ($filter == 'year') {
            $now       = Carbon::now();
            $StartDate = date('Y-01-01 00:00:00');
            $EndDate   = date('Y-12-31 23:59:59');
            return $query->whereBetween($column, [$StartDate, $EndDate]);
        }
    }

    /**
     * Custom Fixed Query
     * @param [type] $data_type   [description]
     * @param [type] $time_period [description]
     */
    public function FixedQuery($data_type, $time_period)
    {
        $ids = [];
        if ($data_type == 'best_seller_books') {
            $ids = Cache::remember($data_type . '_' . $time_period, 5 * 30, function () use ($time_period) {
                $data = DB::table('sales_matrics');
                $data = $this->dateFilter($data, $time_period, 'created_at');
                $data = $data->groupBy('book_id')->select(['book_id', DB::RAW('COUNT(book_id) as total')])->orderBy('total', 'desc')->take(15)->get()->pluck('book_id')->toArray();
                return $data;
            });
        }

        if ($data_type == 'best_seller_author') {
            $ids = Cache::remember($data_type . '_' . $time_period, 5 * 30, function () use ($time_period) {
                $data = DB::table('sales_matrics');
                $data = $this->dateFilter($data, $time_period, 'created_at');
                $data = $data->groupBy('author_id')->select(['author_id', DB::RAW('COUNT(author_id) as total')])->orderBy('total', 'desc')->take(15)->get()->pluck('author_id')->toArray();
                return $data;
            });
        }

        if ($data_type == 'last_sold_books') {
            $ids = Cache::remember($data_type, 5 * 30, function () {
                $invoice = DB::table('invoices')->whereIn('status', [2, 3, 4, 7])->orderBy('id', 'desc')->take(40)->get()->pluck('id')->toArray();
                return DB::table('invoice_metas')->whereIn('id', $invoice)->groupBy('book_id')->take(15)->get()->pluck('book_id')->toArray();
            });
        }

        if ($data_type == 'new_published_books') {
            $query = DB::table('books')->where('status', 1)
                ->orderBy('published_at', 'desc')->take(15)->get();
        }

        if ($ids && in_array($data_type, ['best_seller_books', 'last_sold_books'])) {
            $query = Cache::remember('d_' . $data_type . '_' . $time_period, 5 * 30, function () use ($ids) {
                return DB::table('books')->where('status', 1)
                    ->whereIn('id', $ids)
                    ->orderByRaw('FIELD(id, ' . implode(',', $ids) . ')')
                    ->take(15)->get();
            });
        }

        if ($ids && in_array($data_type, ['best_seller_author'])) {
            $query = Cache::remember('d_' . $data_type . '_' . $time_period, 5 * 30, function () use ($ids) {
                return DB::table('authors')
                    ->whereIn('id', $ids)
                    ->orderByRaw('FIELD(id, ' . implode(',', $ids) . ')')
                    ->take(15)->get();
            });
        }

        return (@$query) ? $query : false;
    }

}
