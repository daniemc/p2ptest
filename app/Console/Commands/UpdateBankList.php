<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PlaceToPay\ConnectionService;
use Cache;
class UpdateBankList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateBankList';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update bank list from Place To Pay WS';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $service = new ConnectionService();
        $bankList = $service->action('getBankList', $service->auth());
        Cache::forever('bankList', $bankList->getBankListResult->item);
    }
}
