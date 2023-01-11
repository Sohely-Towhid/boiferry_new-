<?php

namespace App\Http\Controllers;

use App\Jobs\pdf2webp;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Ebook;
use App\Models\Library;
use App\Models\Publication;
use App\Models\Requisition;
use Artisan;
use Auth;
use DB;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Nullix\CryptoJsAes\CryptoJsAes;
use Response;
use Storage;
use Str;
use Yajra\DataTables\Html\Builder;

class BookController extends Controller
{

    /**
     * BTL Controller Template
     *
     */
    public function __construct()
    {
        $this->image_lg     = [664, 1000];
        $this->image_md     = [332, 500];
        $this->image_sm     = [133, 200];
        $this->image_xs     = [66, 100];
        $this->image_column = 'column';
        // $this->vendor       = $this->user->vendor;
    }

    /**
     * Save image with redactor driver
     * Saves image in 3 size + main source
     *
     * @param  \Illuminate\Http\Request  $request (image)
     * @return [type]        [description]
     */
    public function saveImage($image, $input = [])
    {
        $path = $image->store('redactor', 'redactor');

        $lg      = Image::make(public_path('assets/images/' . $path))->resize($this->image_lg[0], $this->image_lg[1]);
        $lg_path = public_path('assets/images/' . str_replace("redactor/", "redactor/lg_", $path));
        $lg->save($lg_path, 100);

        $md      = Image::make(public_path('assets/images/' . $path))->resize($this->image_md[0], $this->image_md[1]);
        $md_path = public_path('assets/images/' . str_replace("redactor/", "redactor/md_", $path));
        $md->save($md_path, 100);

        $sm      = Image::make(public_path('assets/images/' . $path))->resize($this->image_sm[0], $this->image_sm[1]);
        $sm_path = public_path('assets/images/' . str_replace("redactor/", "redactor/sm_", $path));
        $sm->save($sm_path, 50);

        $xs      = Image::make(public_path('assets/images/' . $path))->resize($this->image_xs[0], $this->image_xs[1]);
        $xs_path = public_path('assets/images/' . str_replace("redactor/", "redactor/xs_", $path));
        $xs->save($xs_path, 30);

        $input[$this->image_column] = url('assets/images/' . $path);
        return $input;
    }

    /**
     * Save image with redactor driver
     * Saves image in 3 size + main source
     *
     * @param  \Illuminate\Http\Request  $request (image)
     * @return [type]        [description]
     */
    public function saveOgImage($image, $input = [])
    {
        $path    = $image->store('redactor', 'redactor');
        $lg      = Image::make(public_path('assets/images/' . $path))->resize(1200, 630);
        $lg_path = public_path('assets/images/' . $path);
        $lg->save($lg_path, 100);
        return $path;
    }

    /**
     * Display a listing of the resource.
     * Datatable Ajax & Html
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $builder)
    {
        $user    = $auth    = $this->user();
        $layout  = ($user->role == 'vendor') ? 'seller' : 'admin';
        $type    = 'All';
        $columns = [
            ['data' => 'id', 'name' => 'id', 'title' => '#'],
            ['data' => 'isbn', 'name' => 'isbn', 'title' => 'ISBN'],
            ['data' => 'title_bn', 'name' => 'title_bn', 'title' => 'Title'],
            ['data' => 'author_bn', 'name' => 'author_bn', 'title' => 'Author'],
            ['data' => 'publisher_name', 'name' => 'publications.name_bn', 'title' => 'Publication'],
            ['data' => 'published_at', 'name' => 'published_at', 'title' => 'First Print'],
            ['data' => 'rate', 'name' => 'rate', 'title' => 'Rate (Tk)'],
            ['data' => 'sale', 'name' => 'sale', 'title' => 'Sale (Tk)'],
            ['data' => 'stock', 'name' => 'stock', 'title' => 'Stock'],
        ];

        // Conditional Column
        //if ($user->vendor_id) {
        // $columns[] = ['data' => '__name', 'name' => '__name', 'title' => '__name'];
        //}

        $columns[] = ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'render' => 'window.book_status(full.status)'];
        if (in_array($user->role, ['admin', 'manager', 'key-account-manager', 'product-manager', 'vendor'])) {
            $columns[] = ['defaultContent' => '', 'width' => '85px', 'data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'render' => $this->dtButton('book', ['delete', 'view'])];
        }
        if ($request->ajax()) {
            $query = DB::table('books');
            if ($user->vendor_id) {
                $query = $query->where('books.vendor_id', $this->user->vendor_id);
            }
            $_columns = [];

            if ($request->type == 'pending') {
                $query->where('books.status', 0);
            }
            if ($request->type == 'active') {
                $query->where('books.status', 1);
            }
            if ($request->type == 'stopped') {
                $query->where('books.status', 2);
            }
            if ($request->type == 'pre-order') {
                $query->where('books.pre_order', 1);
            }
            if ($request->type == 'stockout') {
                $query->where('books.stock', "<", 1)->where('books.status', 1);
            }
            if ($request->type == 'ebook') {
                $query->whereNotNull('books.ebook');
            }
            if ($request->publisher) {
                $query->where('books.publisher_id', $request->publisher);
            }
            if ($request->author) {
                $query->where('books.author_id', $request->author);
            }
            if ($request->vendor) {
                $query->where('books.vendor_id', $request->vendor);
            }
            // Write Extra Query if Needed
            // Join Query
            $query = $query->join('publications', 'books.publisher_id', '=', 'publications.id');
            // }
            //
            $_columns[] = 'publications.name_bn as publisher_name';

            foreach ($columns as $key => $value) {
                if (!in_array($value['data'], ['action', 'publisher_name'])) {
                    $_columns[] = "books." . $value['data'];
                }
            }

            return datatables()->of($query->select($_columns))->toJson();
        }

        $export = (in_array($auth->role, ['admin', 'operations'])) ? "exportButton();" : '';

        $html = $builder->columns($columns)
            ->parameters([
                'searchHighlight' => true,
                'initComplete'    => "function(){
                    var page = Number(" . abs($request->get('page', 0)) . ");
                    page = (page>0) ? page - 1 : page;
                    if(page) { this.api().page(page).draw('page'); }
                }",
                "drawCallback"    => "function(){ " . $export . " }",
                'rowCallback'     => "function(row, data, displayNum, displayIndex, dataIndex) {
                    // customize cell html
                    // $('td:eq(1)', row).html('<a href=\"\">' + data.column + '</a>');
                }",
            ]);

        if ($request->type) {
            $type = ucfirst($request->type);
        }

        return view('book.index')
            ->with('layout', $layout)
            ->with('type', $type)
            ->with('html', $html);
    }

    /**
     * Book Index JSON
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function apiIndex(Request $request)
    {
        $items = Book::where('status', 1)->whereNotNull('ebook');
        if (!empty($request->author)) {
            $author = Author::where('slug', $request->author)->firstOrFail();
            $items  = $items->where('author_id', $author->id);
        }
        if (!empty($request->subject)) {
            $subject = Category::where('slug', $request->subject)->firstOrFail();
            $items   = $items->where('category_id', $subject->id);
        }
        if (!empty($request->publisher)) {
            $publisher = Publication::where('slug', $request->publisher)->firstOrFail();
            $items     = $items->where('publisher_id', $publisher->id);
        }
        if (!empty($request->q)) {
            $items = $items->whereLike(['slug', 'author', 'author_bn', 'title', 'title_bn', 'isbn'], $request->q);
        }
        return $items->select(['slug', 'author', 'author_bn', 'images', 'language', 'isbn', 'number_of_page', 'title', 'title_bn'])->paginate(7 * 3);
    }

    /**
     * App Home Data
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function apiIndexHome(Request $request)
    {
        $data['latest']           = Book::where('status', 1)->whereNotNull('ebook')->orderBy('id', 'desc')->take(10)->get();
        $data['featured_books']   = Book::where('status', 1)->whereNotNull('ebook')->take(10)->get();
        $data['best_modi']        = Book::where('status', 1)->whereNotNull('ebook')->take(10)->get();
        $data['best_rel']         = Book::where('status', 1)->whereNotNull('ebook')->take(10)->get();
        $data['best_islam']       = Book::where('status', 1)->whereNotNull('ebook')->take(10)->get();
        $data['best_science']     = Book::where('status', 1)->whereNotNull('ebook')->take(10)->get();
        $data['best_thir']        = Book::where('status', 1)->whereNotNull('ebook')->take(10)->get();
        $data['best_71']          = Book::where('status', 1)->whereNotNull('ebook')->take(10)->get();
        $data['best_bd']          = Book::where('status', 1)->whereNotNull('ebook')->take(10)->get();
        $data['featured_authors'] = Author::take(10)->get();
        return $this->success($data, 200);
    }

    /**
     * Display a listing of the resource in select2 formate (no pagination).
     * Special Search Feature ID:123 will return one item from given id
     *
     * @return \Illuminate\Http\Response
     */
    public function select(Request $request)
    {
        $query = DB::table('books');
        if ($request->has('q') && !empty(request()->q)) {
            $q = "%" . trim($request->q) . "%";
            if (preg_match("/id:([0-9,]+)/", $request->q, $m)) {
                $query = $query->whereIn('id', explode(",", $m[1]));
            } else {
                $query = $query->whereLike(['title', 'title_bn'], $q);
            }
        }
        $items         = $query->select(['id', 'title_bn as name'])->take(30)->get()->toArray();
        $re['results'] = $items;
        return $re;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = $this->user();
        if ($user->role == 'vendor' && $this->vendor->book == false) {
            return abort(403);
        }
        if (!in_array($user->role, ['admin', 'manager', 'key-account-manager', 'product-manager', 'vendor'])) {
            return abort(403);
        }
        $layout = ($user->role == 'vendor') ? 'seller' : 'admin';
        return view('book.create')->with('layout', $layout);
    }

    /**
     * Insert New Author / Update Status
     * @return [type] [description]
     */
    public function authorCorrection($book)
    {
        $slug   = Str::of($book->author)->slug('-');
        $author = Author::where('name', $book->author)->orWhere('slug', $slug)->first();
        if ($author) {
            $author->name = $book->author;
            if ($book->author_bn) {
                $author->name_bn = $book->author_bn;
            }
            $author->save();
        } else {
            $author = Author::create(['name' => $book->author, 'name_bn' => $book->author_bn, 'slug' => $slug]);
        }
        return $author->id;
    }

    public function authorCorrectionData($data)
    {
        $slug   = Str::of($data['name'])->slug('-');
        $author = Author::where('name', $data['name'])->orWhere('slug', $slug)->first();
        if ($author) {
            $author->name = $data['name'];
            if ($data['name_bn']) {
                $author->name_bn = $data['name_bn'];
            }
            $author->save();
        } else {
            $author = Author::create(['name' => $data['name'], 'name_bn' => $data['name_bn'], 'slug' => $slug]);
        }
        $data['id']   = $author->id;
        $data['slug'] = (string) $author->slug;
        return $data;
    }

    public function slugFix($slug, $id = false)
    {
        if ($id > 0 && Book::where('slug', $slug)->where('id', '!=', $id)->first()) {
            $slug = $slug . rand(1, 100);
            return $this->slugFix($slug, $id);
        }
        if ($id == false && Book::where('slug', $slug)->first()) {
            $slug = $slug . rand(1, 100);
            return $this->slugFix($slug, $id);
        }
        return $slug;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $this->user();
        /* if ($this->vendor->book == false) {
        return abort(404);
        }*/
        if (!in_array($user->role, ['admin', 'key-account-manager', 'product-manager', 'vendor'])) {
            return abort(403);
        }

        $request->validate([
            'language'       => 'required',
            'title'          => 'required',
            'title_bn'       => 'required',
            'author'         => 'required',
            'author_bn'      => 'required',
            'images'         => 'required',
            'publisher_id'   => 'required|numeric',
            'category_id'    => 'required|numeric',
            'buy'            => 'numeric',
            'published_at'   => 'required|date_format:Y-m-d',
            'rate'           => 'required|numeric',
            'sale'           => 'required|numeric|lte:rate',
            'number_of_page' => 'required|numeric',
            'stock'          => 'required|numeric',
            'preview'        => 'nullable|mimes:pdf',
            'ebook'          => 'nullable|mimes:epub',
            'og_image_file'  => 'nullable|image',
        ]);

        $input                  = $request->except(['status', 'vendor_id', 'point', 'rating_review', '_images', 'subject_id']);
        $input['vendor_id']     = (in_array($user->role, ['admin', 'key-account-manager', 'product-manager'])) ? $request->vendor_id : $this->vendor->id;
        $input['status']        = 0;
        $input['free_page']     = (int) @$input['free_page'];
        $input['ebook_type']    = (string) (@$input['ebook_type']) ? $input['ebook_type'] : 'unicode';
        $input['images']        = explode(',', clean($request->images));
        $input['rating_review'] = ['rating' => 0, 'rating_total' => 0, 'review' => 0, 'review_total' => 0, 'rating_list' => ['star_5' => 0, 'star_4' => 0, 'star_3' => 0, 'star_2' => 0, 'star_1' => 0]];

        if ($request->preview) {
            $just_path = 'temp-pdf/' . sha1(rand(10, 99) . date('Y-m-d H:i:s') . rand(10, 100)) . '.pdf';
            file_put_contents(storage_path($just_path), $request->preview->get());
            $input['preview'] = $just_path;
        }
        if ($request->ebook) {
            $input['ebook'] = $request->ebook->storeAs('ebooks', sha1(rand(10, 99) . date('Y-m-d H:i:s') . rand(10, 100)), 'local');
        }

        $input['seo'] = ['keywords' => $request->keywords, 'meta_description' => $request->meta_description, 'og_image' => ($request->og_image) ? $request->og_image : str_replace('redactor/', 'redactor/lg_', @$input['images'][0])];

        if ($request->og_image_file) {
            $input['seo']['og_image'] = $this->saveOgImage($request->og_image_file);
        }
        $input['slug'] = Str::slug($input['title']);
        $input['slug'] = $this->slugFix($input['slug']);

        $item            = Book::create($input);
        $item->author_id = $this->authorCorrection($item);
        $author          = [];
        if (is_array($request->author_type)) {
            foreach ($request->author_type as $key => $value) {
                if (@$request->author_type[$key] && @$request->author_name[$key] && @$request->author_name_bn[$key]) {
                    $author[] = $this->authorCorrectionData(['type' => $request->author_type[$key], 'name' => $request->author_name[$key], 'name_bn' => $request->author_name_bn[$key]]);
                }
            }
        }
        $item->others = $author;
        $item->fbt    = $request->fbt;
        $item->save();

        if ($item->preview) {
            pdf2webp::dispatch($item);
        }

        return redirect('book')->with('success', 'New Book Created Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        $layout = ($user->role == 'vendor') ? 'seller' : 'admin';
        return view('book.show')->with('item', $book)->with('layout', $layout);
    }

    public function apiShow($slug)
    {
        $user = Auth::user();
        $book = Book::with('publication')->where('slug', $slug)->where('status', 1)->whereNotNull('ebook')
            ->select(['id', 'slug', 'publisher_id', 'author', 'author_bn', 'images', 'language', 'isbn', 'number_of_page', 'title', 'title_bn', 'ebook', 'ebook_rate', 'ebook_sale', 'subscription', 'description', 'free_page', 'ebook_type'])
            ->firstOrFail();
        $own       = Ebook::where('book_id', $book->id)->where('user_id', $user->id)->where('status', 1)->first();
        $book->own = ($own) ? true : false;
        if ($user->email == 'test@boiferry.com') {
            $book->own = true;
        }
        if ($book->ebook) {
            $book->ebook = url('api/ebook.epub?file=' . $book->ebook);
            if ($user->email != 'test@boiferry.com') {
                $library      = Library::where('user_id', $user->id)->where('book_id', $book->id)->first();
                $book->others = @$library->others;
            } else {
                $book->others = null;
            }
            return $book;
        }
        return $book;
    }

    /**
     * Display the specified resource in JSON.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function apiRead($slug)
    {
        $user = Auth::user();
        $book = Book::where('slug', $slug)->where('status', 1)
            ->select(['id', 'slug', 'author', 'author_bn', 'images', 'language', 'isbn', 'number_of_page', 'title', 'title_bn', 'ebook', 'free_page', 'ebook_type'])
            ->firstOrFail();
        if ($book->ebook) {
            $book->ebook  = url('api/ebook.epub?file=' . $book->ebook);
            $library      = Library::where('user_id', $user->id)->where('book_id', $book->id)->first();
            $book->others = @$library->others;
            return $book;
        }
        return abort(404);
    }

    /**
     * Display the Ebook.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function apiEbook(Request $request)
    {
        $file = $request->file;
        if (preg_match("/^local.*/", $file)) {
            if (Storage::disk('local')->exists($file)) {
                $token = $request->bearerToken();
                if (!$token) {
                    $token = $request->api_token;
                }
                $data      = base64_encode(Storage::disk('local')->get($file));
                $password  = substr($token, 2, 22);
                $encrypted = CryptoJsAes::encrypt($data, $password);
                return $encrypted;
            }
        }
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        $user = $this->user();
        if ($user->role == 'vendor' && $book->vendor_id != $this->vendor->id) {
            return abort(403);
        }
        if ($user->role == 'vendor' && $this->vendor->book == false) {
            return abort(404);
        }
        if (!in_array($user->role, ['admin', 'key-account-manager', 'product-manager', 'vendor'])) {
            return abort(403);
        }
        $layout = ($user->role == 'vendor') ? 'seller' : 'admin';
        return view('book.edit')->with('item', $book)->with('layout', $layout);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        $user = $this->user();
        if ($user->role == 'vendor' && $this->vendor->book == false) {
            return abort(404);
        }

        if ($user->role == 'vendor' && $book->vendor_id != $this->vendor->id) {
            return abort(403);
        }

        if (!in_array($user->role, ['admin', 'key-account-manager', 'product-manager', 'vendor'])) {
            return abort(403);
        }

        $request->validate([
            'language'       => 'required',
            'title'          => 'required',
            'title_bn'       => 'required',
            'author'         => 'required',
            'author_bn'      => 'required',
            'images'         => 'required',
            'publisher_id'   => 'required|numeric',
            'category_id'    => 'required|numeric',
            'published_at'   => 'required|date_format:Y-m-d',
            'rate'           => 'required|numeric',
            'sale'           => 'required|numeric|lte:rate',
            'number_of_page' => 'required|numeric',
            'stock'          => 'required|numeric',
            'preview'        => 'nullable|mimes:pdf',
            'ebook'          => 'nullable|mimes:epub',
            'og_image_file'  => 'nullable|image',
            'buy'            => 'numeric',
            'actual_stock'   => 'numeric',
        ]);

        $input               = $request->except(['status', 'vendor_id', 'point', 'rating_review', '_images', 'subject_id']);
        $input['images']     = explode(',', clean($request->images));
        $input['free_page']  = (int) @$input['free_page'];
        $input['ebook_type'] = (string) (@$input['ebook_type']) ? $input['ebook_type'] : 'unicode';
        $input['vendor_id']  = (in_array($user->role, ['admin', 'key-account-manager', 'product-manager'])) ? $request->vendor_id : $book->vendor_id;
        // $input['rating_review'] = ['rating' => 0, 'rating_total' => 0, 'review' => 0, 'review_total' => 0];

        // Use to save image
        if ($request->preview) {
            $just_path = 'temp-pdf/' . sha1(rand(10, 99) . date('Y-m-d H:i:s') . rand(10, 100)) . '.pdf';
            file_put_contents(storage_path($just_path), $request->preview->get());
            $input['preview'] = $just_path;
        }
        if ($request->ebook) {
            $input['ebook'] = $request->ebook->storeAs('local', 'ebooks/' . sha1(rand(10, 99) . date('Y-m-d H:i:s') . rand(10, 100)));
        }

        if (in_array($user->role, ['admin', 'key-account-manager', 'product-manager', 'vendor'])) {
            $input['status'] = $request->status;
        }
        if (!@$input['slug']) {
            $input['slug'] = Str::slug($input['title']);
        }
        $input['slug'] = $this->slugFix($input['slug'], $book->id);
        $input['seo']  = ['keywords' => $request->keywords, 'meta_description' => $request->meta_description, 'og_image' => ($request->og_image) ? $request->og_image : str_replace('redactor/', 'redactor/lg_', @$input['images'][0])];
        if ($request->og_image_file) {
            $input['seo']['og_image'] = $this->saveOgImage($request->og_image_file);
        }

        if ($request->delete_preview == 'yes') {
            $input['preview'] = null;
        }
        if ($request->ebook == 'yes') {
            $input['ebook'] = null;
        }

        $book->update($input);
        $this->authorCorrection($book);

        $author = [];
        if (is_array($request->author_type)) {
            foreach ($request->author_type as $key => $value) {
                if (@$request->author_type[$key] && @$request->author_name[$key] && @$request->author_name_bn[$key]) {
                    $author[] = $this->authorCorrectionData(['type' => $request->author_type[$key], 'name' => $request->author_name[$key], 'name_bn' => $request->author_name_bn[$key]]);
                }
            }
        }
        $book->others    = $author;
        $book->author_id = $this->authorCorrection($book);
        $book->fbt       = $request->fbt;
        $book->save();

        if ($request->preview) {
            pdf2webp::dispatch($book);
        }

        return redirect('book/' . $book->id . '/edit')->with('success', 'Book Details Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        // $book->delete();
        return ''; // 204 code
    }

    /**
     * Book Bulk Update
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function bulkPrice(Request $request)
    {
        $user = $this->user();
        if ($user->role == 'vendor') {
            return abort(404);
        }

        if (!in_array($user->role, ['admin', 'product-manager'])) {
            return abort(403);
        }

        $layout = ($user->role == 'vendor') ? 'seller' : 'admin';
        return view('book.bulk')->with('layout', $layout);
    }

    public function ManualPage($page = 0)
    {
        $request = request();
        $limit   = 500;
        echo $limit . " start\n";
        $books = DB::table('books')->where('status', 1)->where('rate', '>', 1)
            ->select(['id', 'rate', 'sale'])
            ->skip($page * $limit)
            ->take($limit)
            ->get();

        if (count($books) == 0) {
            Artisan::call('cache:clear');
            return true;
        }

        foreach ($books as $book) {
            if ($book->rate > 0) {
                $c_p = $book->rate - $book->sale;
                if ($c_p > 0) {
                    $c_p = (100 / $book->rate) * $c_p;
                }
                $c_p += ($request->type == 'increment') ? +$request->amount : -$request->amount;
                if ($c_p) {
                    $sale = ($book->rate * (100 - $c_p)) / 100;
                    var_dump(['id' => $book->id, 'sale' => $sale, 'rate' => $book->rate, 'cp' => $c_p]);
                    DB::table('books')->where('id', $book->id)->update(['sale' => $sale]);
                    echo $book->id . " >> D\n";
                } else {
                    var_dump(['id' => $book->id, 'sale' => $book->rate, 'rate' => $book->rate, 'cp' => $c_p]);
                    DB::table('books')->where('id', $book->id)->update(['sale' => $book->rate]);
                    echo $book->id . " >> D\n";
                }
            }
        }

        $page += 1;
        echo $limit . " done\n";
        return $this->ManualPage($page);
    }

    /**
     * Book Bulk Update Process
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function postBulkPrice(Request $request)
    {
        $user = $this->user();
        if ($user->role == 'vendor') {
            return abort(404);
        }

        if (!in_array($user->role, ['admin', 'product-manager'])) {
            return abort(403);
        }

        if ($request->bulk_per == 'yes') {
            $request->validate([
                'type'   => 'required',
                'amount' => 'required|numeric',
            ]);

            $this->ManualPage();
            return "**ok**";
        }

        $request->validate([
            'author_id'    => 'required_without_all:publisher_id,vendor_id',
            'publisher_id' => 'required_without_all:author_id,vendor_id',
            'vendor_id'    => 'required_without_all:author_id,publisher_id',
            'amount'       => 'required|numeric',
        ]);

        $books = new Book();
        if ($request->author_id) {
            $books = $books->where('author_id', $request->author_id);
        }
        if ($request->publisher_id) {
            $books = $books->where('publisher_id', $request->publisher_id);
        }
        if ($request->vendor_id) {
            $books = $books->where('vendor_id', $request->vendor_id);
        }
        $books = $books->get();
        $total = count($books);

        if ($request->amount > 0) {
            $amount = 100 - $request->amount;
            foreach ($books as $key => $book) {
                $book->sale = ($book->rate * $amount) / 100;
                $book->save();
            }
        }

        return redirect('book/bulk-price')->with('success', $total . " book price updated with " . $request->amount . "% discount.");

    }

    /**
     * Facebook Feed
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function fbFeed(Request $request)
    {
        $headers = ['Content-Type' => 'text/csv'];
        return Response::download(storage_path('books.csv'), 'books.csv', $headers);
    }

    /**
     * Make PDF watermark
     * @param  [type] $file [description]
     * @return [type]       [description]
     */
    public function pdfWaterMark($file)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return false;
        }
        $cmd  = 'qpdf --verbose --replace-input --overlay ' . public_path('assets/images/watermark.pdf') . ' --repeat=1 -- ' . $file;
        $data = exec($cmd);
        if (preg_match(("/qpdf.*wrote file/"), $data)) {
            $pdf = 'data:application/pdf;base64,' . base64_encode(file_get_contents($file));
            file_put_contents($file, $pdf); //save as base64
            return true;
        } else {
            return false;
        }
    }

    /**
     * Custom Requisition
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function requisition(Request $request)
    {
        $items = Requisition::with('book', 'book.publication')->get();
        return view('book.requisition')->with('items', $items);
    }

    /**
     * Custom Requisition
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function requisitionPost(Request $request)
    {
        $request->validate([
            'id'    => 'required',
            'qnt'   => 'required',
            'shelf' => 'required',
            'buy'   => 'required',
        ]);

        $item = Requisition::findOrFail($request->id);

        $book = Book::where('id', $item->product_id)->update(['shelf' => $request->shelf]);
        Book::find($item->product_id)->increment('stock', $request->qnt);
        Book::find($item->product_id)->increment('actual_stock', $request->qnt);

        if ($request->qnt >= $item->quantity) {
            $item->delete();
        } else {
            $item->quantity -= $request->qnt;
            $item->save();
        }

        return redirect('book/requisition')->with('success', 'Stock Updated!');
    }
}
