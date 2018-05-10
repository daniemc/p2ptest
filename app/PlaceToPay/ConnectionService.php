<?php

namespace App\PlaceToPay;

use App\PlaceToPay\Contracts\ConnectionTemplate;

use SoapClient;

class ConnectionService implements ConnectionTemplate{

    protected $id;
    protected $key;
    protected $wsdl;
    protected $location;
    protected $connection;

    /**
     * Init connection parammeters taken from
     * project config
     */
    public function __construct() {

        $this->id = config('services.placeToPay.id');
        $this->key = config('services.placeToPay.transactionalKey');
        $this->wsdl = config('services.placeToPay.wsdl');
        $this->location = config('services.placeToPay.location');

    }

    /**
     * Execute service method and return is result
     *
     * @param String $action
     * @param Array $params
     * @return SoapResult
     */
    public function action($action, $params) {

        $options = array(
            "trace" => 1,
            "exception" => 1,
            "location" => $this->location,
        );

        try {
            $soap = new SoapClient($this->wsdl, $options);
            return $soap->$action($params);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }


    }

    /**
     * Setup params to authenticate in WS
     * needs to be immplemented with two other methods
     * Protected getTranKey()
     * Protected seed()
     *
     * @return Array
     */
   public function auth() {
    return [
        "auth" => array(
            "login" => $this->id,
            "tranKey" => $this->getTranKey(),
            "seed" => $this->seed(),
            "additional" => [],
        )
    ];
   }

   /**
    * Get transaction key encripted and combined with
    * Seed param
    *
    * @return String
    */
   protected function getTranKey() {
       return sha1($this->seed() . $this->key);
   }

   /**
    * Return Seed param to send to WS Auth
    *
    * @return String
    */
   protected function seed() {
       return date('c');
   }
}
