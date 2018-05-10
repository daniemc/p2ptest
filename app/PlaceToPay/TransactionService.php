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

    public function beginTransaction($transaction, $person) {
        $this->setPerson($person);
        $this->transaction = $transaction;
        return $this->initTransaction();
    }

    public function transactionInfo($transactionId) {
        $this->fillInfoTransactionParams($transactionId);
        $transactionInfo = $this->action('getTransactionInformation', $this->params);

        TransactionPersist::fillTransactionAttempt($transactionInfo->getTransactionInformationResult);
        TransactionJobValidator::Initial($transactionInfo->getTransactionInformationResult->responseCode, $transactionId);

        return [
            "result" => $transactionInfo->getTransactionInformationResult,
        ];
    }

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


    protected function getAuth() {
        $this->auth = $this->auth();
    }

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

    protected function fillInfoTransactionParams($transactionId) {
        $this->getAuth();

        $this->params = [
            'auth' => $this->auth['auth'],
            'transactionID' => $transactionId,
        ];
    }


}
