<?php

use App\Models\Book;
use App\Phonetic;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id')->unsigned();
            $table->string('slug', 100);
            $table->string('language', 50);
            $table->string('isbn', 50)->nullable();
            $table->string('type', 30)->nullable();
            $table->string('title');
            $table->string('title_bn');
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->text('others')->nullable();
            $table->integer('author_id')->unsigned();
            $table->string('author');
            $table->string('author_bn');
            $table->text('images')->nullable();
            $table->string('preview')->nullable();
            $table->string('ebook')->nullable();
            $table->string('audio')->nullable();
            $table->integer('publisher_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->date('published_at')->nullable();
            $table->decimal('rate', 13, 2)->unsigned();
            $table->decimal('sale', 13, 2)->unsigned();
            $table->integer('number_of_page')->unsigned();
            $table->integer('stock');
            $table->integer('point')->unsigned()->default(0);
            $table->string('shelf', 20)->nullable();
            $table->text('seo')->nullable();
            $table->string('rating_review', 190)->default(json_encode(['rating' => 0, 'rating_total' => 0, 'review' => 0, 'review_total' => 0, 'rating_list' => ['star_5' => 0, 'star_4' => 0, 'star_3' => 0, 'star_2' => 0, 'star_1' => 0]]));
            $table->tinyInteger('status')->unsigned()->default(0);
            $table->timestamps();
        });
        $ph    = new Phonetic();
        $books = ['নন্দিত নরকে', 'শঙ্খনীল কারাগার', 'আমার আছে জল', 'ফেরা', 'প্রিয়তমেষু', 'সম্রাট', 'আকাশ জোড়া মেঘ', 'দ্বৈরথ', 'সাজঘর', 'এইসব দিনরাত্রি', 'অন্ধকারের গান', 'সমুদ্র বিলাস', 'অয়োময়', 'বহুব্রীহি', 'নীল অপরাজিতা', 'দুই দুয়ারী', 'আশাবরী', 'কোথাও কেউ নেই', 'দি একসরসিস্ট', 'পাখি আমার একলা পাখি', 'জলপদ্ম', 'আয়নাঘর', 'কৃষ্ণপক্ষ', 'জনম জনম', 'তুমি আমায় ডেকেছিলে ছুটির নিমন্ত্রণে', 'জল জোছনা', 'পোকা', 'মন্দ্রসপ্তক', 'তিথির নীল তোয়ালে', 'নবনী', 'ছায়াবীথি', 'জয়জয়ন্তী', 'যখন গিয়েছে ডুবে পঞ্চমীর চাঁদ', 'তোমাকে', 'শ্রাবণ মেঘের দিন', 'গৌরীপুর জংশন', 'জীবনকৃষ্ণ মেমোরিয়াল হাইস্কুল', 'পারুল ও তিনটি কুকুর', 'পেন্সিলে আঁকা পরী', 'কবি', 'আমাদের শাদা বাড়ি', 'জলকন্যা', 'দূরে কোথায়', 'রুমালী', 'অপেক্ষা', 'মেঘ বলেছে যাব যাব', 'কালো যাদুকর', 'চৈত্রের দ্বিতীয় দিবস', 'মীরার গ্রামের বাড়ী', 'ইস্টিশন', 'এই মেঘ, রৌদ্রছায়া', 'রূপার পালঙ্ক', 'বৃষ্টি বিলাস', 'যদিও সন্ধ্যা', 'আজ চিত্রার বিয়ে', 'তেতুল বনে জোছনা', 'বৃষ্টি ও মেঘমালা', 'মৃন্ময়ী', 'কুটু মিয়া', 'নীল মানুষ', 'আসমানীরা তিন বোন', 'বাসর', 'একজন মায়াবতী', 'উড়ালপঙ্খি', 'অচিনপুর', 'আজ আমি কোথাও যাব না', 'আমি এবং কয়েকটি প্রজাপতি', 'রজনী', 'রোদনভরা এ বসন্ত', 'একা একা', 'প্রথম প্রহর', 'দিনের শেষে', 'নক্ষত্রের রাত', 'এপিটাফ', 'এই বসন্তে', 'লীলাবতী', 'সেদিন চৈত্রমাস', 'অরণ্য', 'কে কথা কয়', 'মৃন্ময়ীর মন ভালো নেই', 'কুহুরানী', 'লিলুয়া বাতাস', 'কিছুক্ষণ', 'মধ্যাহ্ন', 'অপরাহ্ণ', 'অমানুষ', 'চক্ষে আমার তৃষ্ণা', 'দিঘির জলে কার ছায়া গো', 'বাদল দিনের দ্বিতীয় কদম ফুল', 'নির্বাসন', 'সবাই গেছে বনে', 'সে ও নর্তকী', 'মানবী', 'সানাউল্লার মহাবিপদ', 'অন্যদিন', 'চাঁদের আলোয় কয়েকজন যুবক', 'নলিনী বাবু বি.এসসি', 'মাতাল হাওয়া', 'রূপা', 'ম্যাজিক মুনশি', 'একটি সাইকেল এবং কয়েকটি ডাহুক পাখি', 'বাদশাহ নামদার', 'আমরা কেউ বাসায় নেই', 'দাঁড়কাকের সংসার কিংবা মাঝে মাঝে তব দেখা পাই', 'মেঘের ওপর বাড়ি '];
        foreach ($books as $key => $book) {
            Book::create([
                'vendor_id'      => 1,
                'language'       => 'bangla',
                'isbn'           => null,
                'title'          => $ph->str_bn_to_en($book),
                'slug'           => Str::slug($ph->str_bn_to_en($book)),
                'title_bn'       => $book,
                'author_id'      => 1,
                'author'         => 'Humayun Ahmed',
                'author_bn'      => 'হুমায়ূন আহমেদ',
                'publisher_id'   => 1,
                'subject_id'     => 1,
                'category_id'    => 1,
                'published_year' => '2018',
                'rate'           => 100,
                'sale'           => 100,
                'number_of_page' => 100,
                'stock'          => 10,
                'point'          => 10,
                'status'         => 1,
                'rating_review'  => ['rating' => rand(3, 5), 'rating_total' => rand(10, 999), 'review' => 0, 'review_total' => 0, 'rating_list' => ['star_5' => rand(10, 99), 'star_4' => rand(10, 99), 'star_3' => rand(10, 99), 'star_2' => rand(10, 99), 'star_1' => rand(10, 99)]],
                'images'         => ['redactor/' . $book . '.jpg'],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
