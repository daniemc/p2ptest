<?php

namespace App\Http\Controllers\PlaceToPay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PlaceToPay\TransactionService;

use App\Models\PlaceToPay\CreateTransaction;
use App\Models\PlaceToPay\Person;

class TransactionController extends Controller
{
    /**
     * Function callled from client to vegin transaction
     * This is initialy saved in DB
     *
     * @param Request $form
     * @return void
     */
    public function beginTransaction(Request $form) {

        $person = new Person();

        $transaction = new CreateTransaction();
        $transaction->bankcode = $form->bank;
        $transaction->bankInterface = $form->user_type;
        $transaction->returnURL = env('APP_URL_CALLBACK');
        $transaction->reference = 'Compra-'.date('Y-m-d H:i:s');
        $transaction->description = 'Test Payment';
        $transaction->language = app()->getLocale();
        $transaction->currency = 'COP';
        $transaction->totalAmount = $form->cartTotalPrice;
        $transaction->taxAmount = 0.0;
        $transaction->devolutionBase = 0.0;
        $transaction->tipAmount = 0.0;
        $transaction->payer = $person->document;
        $transaction->buyer = $person->document;
        $transaction->shipping = $person->document;
        $transaction->ipAddress = $form->ip();
        $transaction->userAgent = $form->header('User-Agent');
        $transaction->additionalData = '';
        $transaction->save();

        $createTransaction = new TransactionService();
        return $createTransaction->beginTransaction($transaction->id, $person);
    }

    /**
     * Function called in callback view to
     * verify transaction info
     *
     * @param Request $form
     * @return void
     */
    public function validateTransaction(Request $form) {
        $transactionInfo = new TransactionService();
        return $transactionInfo->transactionInfo($form->transactionID);
    }
}
