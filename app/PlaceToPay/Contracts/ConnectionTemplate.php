<?php

namespace App\PlaceToPay\Contracts;

interface ConnectionTemplate {

    /**
     * Must be called to initialize connection service
     *
     * @return void
     */
    public function __construnct();

    /**
     * Execute service method and return is result
     *
     * @param String $action
     * @param Array $params
     * @return SoapResult
     */
    public function action($action, $params);

    /**
     * Setup params to authenticate in WS
     * needs to be immplemented with two other methods
     * Protected getTranKey()
     * Protected seed()
     *
     * @return Array
     */
    public function auth();

}
