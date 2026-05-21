<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function switch($currency)
    {
        $allowed = ['RUB', 'USD', 'EUR'];

        if (!in_array($currency, $allowed)) {
            $currency = 'RUB';
        }

        session(['currency' => $currency]);

        cookie()->queue('currency', $currency, 60 * 24 * 30);

        return back()->with('success', 'Валюта изменена на ' . $this->getCurrencySymbol($currency) . ' ' . $currency);
    }

    private function getCurrencySymbol($currency)
    {
        return match ($currency) {
            'RUB' => '₽',
            'USD' => '$',
            'EUR' => '€',
            default => $currency,
        };
    }
}
