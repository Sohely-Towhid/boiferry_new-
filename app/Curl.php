<?php
namespace App;

/**
 * Basic Curl Lib by Saiful
 */
class Curl
{
    private $ch;
    private $cookie;

    public function __construct()
    {
        $this->cookie = __DIR__ . "/cookie_x.txt";
        $this->ch     = curl_init();
        $this->option(CURLOPT_USERAGENT, $this->agent());
        $this->option(CURLOPT_SSL_VERIFYPEER, false);
        $this->option(CURLOPT_FOLLOWLOCATION, true);
        $this->option(CURLOPT_COOKIESESSION, true);
        $this->option(CURLOPT_VERBOSE, false);
        $this->option(CURLOPT_RETURNTRANSFER, true);
        $this->option(CURLOPT_FRESH_CONNECT, true);
        $this->cookie();
        $this->header = [];
    }

    private function agent()
    {
        return 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36';
    }

    public function option($name, $value)
    {
        curl_setopt($this->ch, $name, $value);
    }

    public function get($url, string $ref = null, $data = [])
    {
        if (!empty($ref)) {
            $this->option(CURLOPT_REFERER, $ref);
        }
        $this->option(CURLOPT_URL, $url . "?" . http_build_query($data));
        $this->option(CURLOPT_POST, false);
        $this->option(CURLOPT_HTTPHEADER, $this->header);
        return curl_exec($this->ch);
    }

    public function post($url, string $ref = null, $post)
    {
        if (!empty($ref)) {
            $this->option(CURLOPT_REFERER, $ref);
        }
        $this->option(CURLOPT_URL, $url);
        $this->option(CURLOPT_POST, true);
        if (is_array($post)) {
            $this->option(CURLOPT_POSTFIELDS, http_build_query($post));
        } else {
            $this->option(CURLOPT_POSTFIELDS, $post);
        }
        $this->option(CURLOPT_HTTPHEADER, $this->header);
        return curl_exec($this->ch);
    }

    public function postRow($url, string $ref = null, string $post)
    {
        $this->header[] = "Content-Type: application/json";
        $this->header[] = "Content-Length: " . strlen($post);
        if (!empty($ref)) {
            $this->option(CURLOPT_REFERER, $ref);
        }
        $this->option(CURLOPT_URL, $url);
        $this->option(CURLOPT_POST, true);
        $this->option(CURLOPT_POSTFIELDS, $post);
        $this->option(CURLOPT_HTTPHEADER, $this->header);
        return curl_exec($this->ch);
    }

    public function error()
    {
        if (curl_error($this->ch)) {
            return curl_error($this->ch);
        } else {
            return false;
        }
    }

    public function cookie()
    {
        $this->option(CURLOPT_COOKIEJAR, $this->cookie);
        $this->option(CURLOPT_COOKIEFILE, $this->cookie);
    }

    public function ch()
    {
        return $this->ch;
    }

    /**
     * Authorization Bearer
     * @param  [type] $token [description]
     * @return [type]        [description]
     */
    public function auth($token)
    {
        $this->header[] = "Authorization: Bearer $token";
    }

    /**
     * XHR
     * @param  [type] $token [description]
     * @return [type]        [description]
     */
    public function xhr($data = '')
    {
        $this->header[] = "X-Requested-With: XMLHttpRequest";
        $this->header[] = "Content-Type: application/json; charset=utf-8";
        if ($data) {
            $this->header[] = $data;
        }
    }

    public function __destruct()
    {
        curl_close($this->ch);
        @unlink($this->cookie);
    }
}
