<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\PlaceToPay\Transaction;

use App\PlaceToPay\TransactionService;

class TransactionPendingValidation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transactionID;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($transactionID)
    {
        $this->transactionID = $transactionID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $actualCode = Transaction::where('transactionID', $this->transactionID)
            ->value('responseCode');

        if ( $actualCode != '3' ) {
            return;
        }

        $transaction = new TransactionService();
        $transaction->transactionInfo($this->transactionID);
    }
}
