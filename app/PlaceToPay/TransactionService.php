<?php

namespace App\PlaceToPay;

use App\PlaceToPay\ConnectionService;
use App\PlaceToPay\TransactionPersist;
use App\PlaceToPay\TransactionJobValidator;

use App\Models\PlaceToPay\CreateTransaction;

class TransactionService extends ConnectionService {

    protected $params;
    protected $transaction;
    protected $person;
    protected $auth;

    /**
     * Method to begin the transaction flow
     * Here is set the person and the local transaction id
     * from controller
     *
     * @param Integer $transaction
     * @param Object $person
     * @return void
     */
    public function beginTransaction($transaction, $person) {
        $this->setPerson($person);
        $this->transaction = $transaction;
        return $this->initTransaction();
    }

    /**
     * Method called from callback and validators to
     * consult transaction state in WS
     *
     * @param Integer $transactionId
     * @return Array
     */
    public function transactionInfo($transactionId) {
        $this->fillInfoTransactionParams($transactionId);
        $transactionInfo = $this->action('getTransactionInformation', $this->params);

        TransactionPersist::fillTransactionAttempt($transactionInfo->getTransactionInformationResult);
        TransactionJobValidator::Initial($transactionInfo->getTransactionInformationResult->responseCode, $transactionId);

        return [
            "result" => $transactionInfo->getTransactionInformationResult,
        ];
    }

    /**
     * Method to send request to WS and create transaction
     * after that, transaction is saved and validators get
     * activated
     *
     * returns transaction info
     * @return Array
     */
    protected function initTransaction() {

        $this->fillNewTransactionParams();
        $transactionResponse = $this->action('createTransaction', $this->params);

        TransactionPersist::createTransaction($transactionResponse->createTransactionResult, $this->transaction);
        TransactionJobValidator::Pending(
            $transactionResponse->createTransactionResult->responseCode,
            $transactionResponse->createTransactionResult->transactionID
        );

        return [
            "result" =>  $transactionResponse->createTransactionResult
        ];
    }

    /**
     * Gets auth data from parent class
     *
     * @return void
     */
    protected function getAuth() {
        $this->auth = $this->auth();
    }

    /**
     * Reorganize person data to match WS
     * params format
     *
     * @param Person $person
     * @return void
     */
    protected function setPerson($person) {
        $this->person = array(
            'document' => $person->document,
            'documentType' => $person->documentType,
            'firstName' => $person->firstName,
            'lastName' => $person->lastName,
            'company' => $person->company,
            'emailAddress' => $person->emailAddress,
            'address' => $person->address,
            'city' => $person->city,
            'province' => $person->province,
            'country' => $person->country,
            'phone' => $person->phone,
            'mobile' => $person->mobile,
        );
    }

    /**
     * Fill create transaction request parameters and sets
     * the params attribute
     *
     * @return void
     */
    protected function fillNewTransactionParams() {
        $this->getAuth();

        $transactionParams = CreateTransaction::find($this->transaction);

        $this->params = [
            'auth' => $this->auth['auth'],
            'transaction' => array(
                'bankCode' => $transactionParams->bankcode,
                'bankInterface' => $transactionParams->bankInterface,
                'returnURL' => $transactionParams->returnURL,
                'reference' => $transactionParams->reference,
                'description' => $transactionParams->description,
                'language' => $transactionParams->language,
                'currency' => $transactionParams->currency,
                'totalAmount' => $transactionParams->totalAmount,
                'taxAmount' => $transactionParams->taxAmount,
                'devolutionBase' => $transactionParams->devolutionBase,
                'tipAmount' => $transactionParams->tipAmount,
                'payer' => $this->person,
                'buyer' => $this->person,
                'shipping' => $this->person,
                'ipAddress' => $transactionParams->ipAddress,
                'userAgent' => $transactionParams->userAgent,
                'additionalData' => $transactionParams->additionalData,
            ),
        ];
    }

    /**
     * Fill transaction info request params and sets
     * the params attribute
     *
     * @return void
     */
    protected function fillInfoTransactionParams($transactionId) {
        $this->getAuth();

        $this->params = [
            'auth' => $this->auth['auth'],
            'transactionID' => $transactionId,
        ];
    }


}
