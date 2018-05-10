<?php

namespace App\PlaceToPay;

use App\Jobs\TransactionInitialValidation;
use App\Jobs\TransactionPendingValidation;

class TransactionJobValidator {

    protected static $initialTime;
    protected static $pendingTime;

    /**
     * Init class with defined time in env file
     * or default
     */
    public function __construct() {
        self::$initialTime = env('INITIAL_TIME_VALIDATION', 7);
        self::$pendingTime = env('PENDINGL_TIME_VALIDATION', 12);
    }

    /**
     * Method to handle initial validation (Job)
     * when transaction is newly created
     *
     * @param Integer $codeReturned
     * @param Integer $transactionId
     * @return void
     */
    public static function Initial($codeReturned, $transactionId) {
        if ($codeReturned == 3) {
            TransactionPendingValidation::dispatch($transactionId)
                ->delay(now()->addMinutes(self::$initialTime));
        }
    }

    /**
     * Method to handle validation (Job)
     * when transaction is pending
     *
     * @param Integer $codeReturned
     * @param Integer $transactionId
     * @return void
     */
    public static function Pending($codeReturned, $transactionId) {
        if ($codeReturned == 3) {
            TransactionPendingValidation::dispatch($transactionId)
                ->delay(now()->addMinutes(self::$pendingTime));
        }
    }
}
