<?php

namespace App\PlaceToPay;

use SoapClient;

class ConnectionService {

    protected $id;
    protected $key;
    protected $wsdl;
    protected $local;
    protected $connection;

    public function __construct() {

        $this->id = config('services.placeToPay.id');
        $this->key = config('services.placeToPay.transactionalKey');
        $this->wsdl = config('services.placeToPay.wsdl');
        $this->local = config('services.placeToPay.local');

    }

    public function action($action, $params) {

        $options = array(
            "trace" => 1,
            "exception" => 1,
            "location" => $this->local,
        );
        $soap = new SoapClient($this->wsdl, $options);

        return $soap->$action($params);
    }

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

   protected function getTranKey() {
       return sha1($this->seed() . $this->key);
   }

   protected function seed() {
       return date('c');
   }
}
