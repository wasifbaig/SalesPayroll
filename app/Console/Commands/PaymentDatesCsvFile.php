<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\BurroughsController;

class PaymentDatesCsvFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PaymentDatesCsvFile {--filename=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CSV file contains the payment dates for the next twelve months.';

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

        $filename = $this->option('filename');

        if( empty($filename))
        {
            $this->info('Filename is required.');
            $this->info('You can call like this - php artisan PaymentDatesCsvFile --filename=');
        }
        else
        {
            $burroughsController = new BurroughsController();

            if($burroughsController->paymentDownloadCsvFile($filename))
                $this->info('File has downloaded');
            else
                $this->error('Error: something went wrong');
        }





    }
}
