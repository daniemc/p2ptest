<?php

namespace App\PlaceToPay;
use App\PlaceToPay\ConnectionService;

use App\Models\PlaceToPay\CreateTransaction;
use App\Models\PlaceToPay\Transaction;

class TransactionService extends ConnectionService{

    protected $params;
    protected $transaction;
    protected $person;
    protected $auth;

    protected function initTransaction() {
        $this->fillTransactionParams();
        $transactionResponse = $this->action('createTransaction', $this->params);
        $this->createTransaction($transactionResponse->createTransactionResult);
        return [
            "result" =>  $transactionResponse->createTransactionResult
        ];
    }

    protected function createTransaction($transactionResponse) {
        $transaction = new Transaction();
        $transaction->transactionID_local = $this->transaction;
        $transaction->transactionID = $transactionResponse->transactionID;
        $transaction->sessionID = $transactionResponse->sessionID;
        $transaction->returnCode = $transactionResponse->returnCode;
        $transaction->trazabilityCode = $transactionResponse->trazabilityCode;
        $transaction->transactionCycle = $transactionResponse->transactionCycle;
        $transaction->bankCurrency = $transactionResponse->bankCurrency;
        $transaction->bankFactor = $transactionResponse->bankFactor;
        $transaction->bankURL = $transactionResponse->bankURL;
        $transaction->responseCode = $transactionResponse->responseCode;
        $transaction->responseReasonCode = $transactionResponse->responseReasonCode;
        $transaction->responseReasonText = $transactionResponse->responseReasonText;
        $transaction->save();
    }

    protected function getAuth() {
        $this->auth = $this->auth();
    }

    protected function fillTransactionParams() {
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

    public function beginTransaction($transaction, $person) {
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
        $this->transaction = $transaction;
        return $this->initTransaction();
    }
}
