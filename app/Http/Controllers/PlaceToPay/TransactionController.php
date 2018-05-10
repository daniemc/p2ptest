<?php

namespace App\Http\Controllers\PlaceToPay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PlaceToPay\TransactionService;

use App\Models\PlaceToPay\CreateTransaction;
use App\Models\PlaceToPay\Person;

class TransactionController extends Controller
{
    public function beginTransaction(Request $form) {

        $person = new Person();

        $transaction = new CreateTransaction();
        $transaction->bankcode = $form->bank;
        $transaction->bankInterface = $form->user_type;
        $transaction->returnURL = env('APP_URL');
        $transaction->reference = 'compra-001';
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
}
