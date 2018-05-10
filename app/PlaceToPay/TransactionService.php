<?php

namespace App\PlaceToPay;
use App\PlaceToPay\ConnectionService;

use App\Models\PlaceToPay\CreateTransaction;
use App\Models\PlaceToPay\Transaction;
use App\Models\PlaceToPay\TransactionAttempt;

use App\Jobs\TransactionInitialValidation;
use App\Jobs\TransactionPendingValidation;

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
        $this->fillTransactionAttempt($transactionInfo->getTransactionInformationResult);

        if ($transactionInfo->getTransactionInformationResult->responseCode == 3) {
            TransactionPendingValidation::dispatch($transactionId)
                ->delay(now()->addMinutes(12));
        }

        return [
            "result" => $transactionInfo->getTransactionInformationResult,
        ];
    }

    protected function initTransaction() {
        $this->fillNewTransactionParams();
        $transactionResponse = $this->action('createTransaction', $this->params);
        $this->createTransaction($transactionResponse->createTransactionResult);
        TransactionInitialValidation::dispatch($transactionResponse->createTransactionResult->transactionID)
            ->delay(now()->addMinutes(7));
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

    protected function fillTransactionAttempt($transactionInfo) {

        $transactionAttempt = new TransactionAttempt();
        $transactionAttempt->transactionID = $transactionInfo->transactionID;
        $transactionAttempt->sessionID = $transactionInfo->sessionID;
        $transactionAttempt->reference = $transactionInfo->reference;
        $transactionAttempt->requestDate = $transactionInfo->requestDate;
        $transactionAttempt->bankProcessDate = $transactionInfo->bankProcessDate;
        $transactionAttempt->onTest = $transactionInfo->onTest;
        $transactionAttempt->returnCode = $transactionInfo->returnCode;
        $transactionAttempt->trazabilityCode = $transactionInfo->trazabilityCode;
        $transactionAttempt->transactionCycle = $transactionInfo->transactionCycle;
        $transactionAttempt->transactionState = $transactionInfo->transactionState;
        $transactionAttempt->responseCode = $transactionInfo->responseCode;
        $transactionAttempt->responseReasonCode = $transactionInfo->responseReasonCode;
        $transactionAttempt->responseReasonText = $transactionInfo->responseReasonText;
        $transactionAttempt->save();

        Transaction::where('transactionID', $transactionInfo->transactionID)
            ->update([
                'responseCode' => $transactionInfo->responseCode,
                'responseReasonCode' => $transactionInfo->responseReasonCode,
                'responseReasonText' => $transactionInfo->responseReasonText,
                'callback_validation' => '1',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }


}
