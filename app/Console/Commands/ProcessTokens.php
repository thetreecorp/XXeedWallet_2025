<?php
    namespace App\Console\Commands;

    use Illuminate\Console\Command;
    use Illuminate\Support\Facades\Artisan;
    use Illuminate\Support\Facades\File;
    class ProcessTokens extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'tokens:process';

        
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
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Process locked and available tokens every 5 minutes';

        /**
         * Execute the console command..
         *
         * @return int
         */
        public function handle()
        {
            
            $keme0 = config('constants.options.keme0') ? config('constants.options.keme0'):  'keme0';
            $keme100 = config('constants.options.keme100') ? config('constants.options.keme100') : 'keme100';

            $unlockTokenId = findCurrencyBySymbol($keme100);
            $targetIdtokenReleased = findCurrencyBySymbol($keme0);
            if($unlockTokenId && $targetIdtokenReleased)
                unlockAndTransferCoins($unlockTokenId->id, $targetIdtokenReleased->id);

            $currentTime = now()->format('Y-m-d H:i:s');
            //File::append(public_path('cronjob.txt'), $currentTime . "\n");

            return 0;
        }
    }