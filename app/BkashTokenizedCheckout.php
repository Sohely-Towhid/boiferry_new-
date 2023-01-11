<?php
namespace App;

/**
 * Bkash Tokenized Checkout by Saiful
 */
class BkashTokenizedCheckout
{
    private $ch;
    private $base_url;
    private $app_key;
    private $app_secret;
    private $username;
    private $password;
    private $token;
    private $callback_url;

    public function __construct()
    {
        $this->ch = curl_init();
        $this->option(CURLOPT_SSL_VERIFYPEER, false);
        $this->option(CURLOPT_RETURNTRANSFER, true);
        $this->option(CURLOPT_FOLLOWLOCATION, true);

        $config             = config('bkash');
        $this->app_key      = $config['bkash_app_key'];
        $this->app_secret   = $config['bkash_app_secret'];
        $this->username     = $config['bkash_username'];
        $this->password     = $config['bkash_password'];
        $this->base_url     = $config['bkash_base_url'] . "/" . $config['bkash_api_version'];
        $this->callback_url = $config['bkash_callback_url'];
    }

    /**
     * Set CURL Options
     * @param  [type] $name  [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function option($name, $value)
    {
        curl_setopt($this->ch, $name, $value);
    }

    /**
     * Post Data and Return JSON Object
     * @param  [type] $url   [description]
     * @param  [type] $param [description]
     * @return [type]        [description]
     */
    public function post($url, $param)
    {
        $post = json_encode($param);
        $this->option(CURLOPT_CUSTOMREQUEST, 'POST');
        $this->option(CURLOPT_POSTFIELDS, $post);
        $this->option(CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        $this->option(CURLOPT_URL, $url);
        $resultdata = curl_exec($this->ch);
        $json       = json_decode($resultdata);
        if (@$json->message == 'The incoming token has expired') {
            $this->getToken(true);
            return $this->post($url, $param);
        }

        // Log::info('API URL:' . $url);
        // Log::info('Request Body:' . json_encode($param));
        // Log::info('API Response:' . json_encode($json));
        return $json;
    }

    /**
     * With bKash Creadintial Auth in Header
     * @return [type] [description]
     */
    public function withAuth()
    {
        $header = ['Content-Type:application/json', "password:$this->password", "username:$this->username"];
        $this->option(CURLOPT_HTTPHEADER, $header);
    }

    /**
     * With bKash Auth Token
     * @return [type] [description]
     */
    public function withToken()
    {
        $header = ['Content-Type:application/json', "authorization: $this->token", "x-app-key: $this->app_key"];
        $this->option(CURLOPT_HTTPHEADER, $header);
    }

    /**
     * Get Token for bKash
     * @return [type] [description]
     */
    public function getToken($force = false)
    {
        $token = cache('bkash_token');
        $token = false;
        // if ($force) {$token = false;}
        if (!$token) {
            // $this->option(CURLOPT_HTTPHEADER, $header);
            $param = ['app_key' => $this->app_key, 'app_secret' => $this->app_secret];
            $url   = "{$this->base_url}/tokenized/checkout/token/grant";
            $this->withAuth();
            // Log::info('API Title : Grant Token');
            $response = $this->post($url, $param);
            if (@$response->msg || @$response->message) {
                return $response;
            } else {
                $token = $response->id_token;
                cache(['bkash_token' => $token], now()->addHours(1));
            }
        }
        $this->token = $token;
        return $token;
    }

    /**
     * Create Payment Request
     * @return [type] [description]
     */
    public function createPayment($amount, $invoice, $reference)
    {
        $this->getToken(true);
        $this->withToken();

        $param = [
            'mode'                  => '0011',
            'amount'                => $amount,
            'intent'                => 'sale',
            'currency'              => 'BDT',
            'merchantInvoiceNumber' => $invoice,
            'payerReference'        => $reference,
            'callbackURL'           => $this->callback_url,
        ];

        $url = "{$this->base_url}/tokenized/checkout/create";
        // Log::info('API Title : Create Payment');
        $response = $this->post($url, $param);
        return ($response) ? @$response->bkashURL : false;
    }

    /**
     * Execute Payment
     * @param  [type] $payment_id [description]
     * @return [type]             [description]
     */
    public function executePayment($payment_id)
    {
        $this->getToken(true);
        $this->withToken();
        $param = ['paymentID' => $payment_id];
        $url   = "{$this->base_url}/tokenized/checkout/execute";
        // Log::info('API Title : Payment Execute');
        $response = $this->post($url, $param);
        return $response;
    }

    /**
     * Check Trnsection
     * @param  [type] $payment_id [description]
     * @return [type]             [description]
     */
    public function checkPayment($payment_id)
    {
        $this->getToken(true);
        $this->withToken();
        $param = ['paymentID' => $payment_id];
        $url   = "{$this->base_url}/tokenized/checkout/payment/status";
        // Log::info('API Title : Payment Status');
        $response = $this->post($url, $param);
        return $response;
    }

    /**
     * Search TrxiD
     * @param  [type] $trxid [description]
     * @return [type]        [description]
     */
    public function searchTrxID($trxid)
    {
        $this->getToken(true);
        $this->withToken();
        $param = ['trxID' => $trxid];
        $url   = "{$this->base_url}/tokenized/checkout/general/searchTransaction";
        // Log::info('API Title : Search Transaction Details');
        $response = $this->post($url, $param);
        return $response;
    }

    /**
     * Distroy Curl
     */
    public function __destruct()
    {
        curl_close($this->ch);
    }
}
