<?php

namespace App\Http\Controllers;

use App\Curl;
use App\Models\Ebook;
use App\Models\Library;
use Illuminate\Http\Request;

class EbookController extends Controller
{

    public function ebookSSL(Request $request)
    {

        if ($request->tran_id) {
            $trns = explode("_", $request->tran_id);
            if (count($trns) != 2) {return abort(404);}
            $ebook = Ebook::findOrFail($trns[0]);

            $ch                   = new Curl();
            $post['val_id']       = $request->val_id;
            $post['store_id']     = config('services.ssl.store_id');
            $post['store_passwd'] = config('services.ssl.store_passwd');
            $post['format']       = 'json';
            $data                 = json_decode($ch->get(config('services.ssl.check') . '?' . http_build_query($post)));

            if ($data) {
                if (in_array($data->status, ['VALID', 'VALIDATED']) && $data->amount >= $ebook->price) {

                    $system_note[] = 'sslcommerz#' . $request->val_id;
                    $system_note[] = 'sslcommerz_trns#' . $request->tran_id;
                    $system_note[] = 'sslcommerz_card#' . $request->card_issuer . " | " . $request->card_brand . '|' . $request->card_issuer_country;

                    $ebook->status  = 1;
                    $ebook->payment = $system_note;
                    $ebook->save();

                    Library::updateOrCreate(['user_id' => $ebook->user_id, 'book_id' => $ebook->book_id], ['user_id' => $ebook->user_id, 'book_id' => $ebook->book_id]);
                    return "Payment Success!";
                }
            } else {
                return "Payment Processing Failed, Please Try Again!";
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ebook  $ebook
     * @return \Illuminate\Http\Response
     */
    public function show(Ebook $ebook)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ebook  $ebook
     * @return \Illuminate\Http\Response
     */
    public function edit(Ebook $ebook)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ebook  $ebook
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ebook $ebook)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ebook  $ebook
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ebook $ebook)
    {
        //
    }
}
