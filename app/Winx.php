<?php
namespace App;

use App\Curl;

/**
 * Basic Winx SDK
 * @author Shaiful Islam
 */
class Winx
{
    private $ch;

    public function __construct()
    {
        $this->ch         = new Curl();
        $this->token      = '200e08196d6d5290c3b7542c54584691e11afbac';
        $this->ch->header = []; // reset header of curl
        $this->ch->auth($this->token);
        $this->ch->xhr();
        $this->base_url = 'https://winx.com.bd';
    }

    /**
     * Create Order
     * @param  [type]  $pickup_id     [description]
     * @param  [type]  $name          [description]
     * @param  [type]  $mobile        [description]
     * @param  [type]  $address       [description]
     * @param  [type]  $package       [description]
     * @param  [type]  $delivery_area [description]
     * @param  [type]  $sale_price    [description]
     * @param  [type]  $cod           [description]
     * @param  boolean $insurance     [description]
     * @return [type]                 [description]
     */
    public function createOrder($merchant_invoice, $pickup_id, $name, $mobile, $address, $package, $delivery_area, $sale_price, $cod, $insurance = false)
    {
        $url    = $this->base_url . '/api/parcel';
        $data   = ['merchant_invoice' => $merchant_invoice, 'pickup_id' => $pickup_id, 'name' => $name, 'mobile' => $mobile, 'address' => $address, 'package' => $package, 'delivery_area' => $delivery_area, 'sale_price' => $sale_price, 'cod' => $cod, 'insurance' => $insurance];
        $output = json_decode($this->ch->postRow($url, '', json_encode($data)));
        return $output;
    }

    /**
     * Get Package List
     * @return [type] [description]
     */
    public function getPackage()
    {
        $url    = $this->base_url . '/api/package/select';
        $output = json_decode($this->ch->get($url, ''));
        return $output;
    }

    /**
     * Get Location List
     * @return [type] [description]
     */
    public function getLocation()
    {
        $url    = $this->base_url . '/api/location/select';
        $output = json_decode($this->ch->get($url, '', ['full' => 'yes']));
        return $output;
    }

    /**
     * Get Pickup List
     * @return [type] [description]
     */
    public function getPickup()
    {
        $url    = $this->base_url . '/api/pickup/select';
        $output = json_decode($this->ch->get($url, ''));
        return $output;
    }

    /**
     * Get Parcel List
     * @return [type] [description]
     */
    public function getParcels()
    {
        $url    = $this->base_url . '/api/parcels';
        $output = json_decode($this->ch->get($url, ''));
        return $output;
    }

    /**
     * Get Single Parcel
     * @return [type] [description]
     */
    public function getParcel($id)
    {
        $url    = $this->base_url . '/api/parcel/' . $id;
        $output = json_decode($this->ch->get($url, ''));
        return $output;
    }

}
