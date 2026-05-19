<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearCurrencyCache extends Command
{
    protected $signature = 'cache:clear-currency-rates';
    protected $description = 'Clear currency rates cache';

    public function handle()
    {
        Cache::forget('currency_rates');
        $this->info('Currency rates cache cleared!');
        return 0;
    }
}
