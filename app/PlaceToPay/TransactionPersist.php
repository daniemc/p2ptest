<?php

namespace App\PlaceToPay;
use App\PlaceToPay\ConnectionService;

use App\Models\PlaceToPay\CreateTransaction;
use App\Models\PlaceToPay\Transaction;
use App\Models\PlaceToPay\TransactionAttempt;

class TransactionPersist {

    /**
     * Create transaction after WS Succes response
     *
     * @param Object $transactionResponse
     * @param Integer $transactionLocal
     * @return void
     */
    public static function createTransaction($transactionResponse, $transactionLocal) {
        $transaction = new Transaction();
        $transaction->transactionID_local = $transactionLocal;
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

    /**
     * Save attempts to valiate transaction state and
     * Update transaction state
     *
     * @param Object $transactionInfo
     * @return void
     */
    public static function fillTransactionAttempt($transactionInfo) {
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
