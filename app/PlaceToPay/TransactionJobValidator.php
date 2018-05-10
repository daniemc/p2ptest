<?php

namespace App\PlaceToPay;

use App\Jobs\TransactionInitialValidation;
use App\Jobs\TransactionPendingValidation;

class TransactionJobValidator {

    protected static $initialTime;
    protected static $pendingTime;

    public function __construct() {
        self::$initialTime = env('INITIAL_TIME_VALIDATION', 7);
        self::$pendingTime = env('PENDINGL_TIME_VALIDATION', 12);
    }

    public static function Initial($codeReturned, $transactionId) {
        if ($codeReturned == 3) {
            TransactionPendingValidation::dispatch($transactionId)
                ->delay(now()->addMinutes(self::$initialTime));
        }
    }

    public static function Pending($codeReturned, $transactionId) {
        if ($codeReturned == 3) {
            TransactionPendingValidation::dispatch($transactionId)
                ->delay(now()->addMinutes(self::$pendingTime));
        }
    }
}
