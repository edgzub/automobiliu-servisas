<?php

namespace App\Services;

class WeekendPricingStrategy implements PricingStrategy
{
    public function calculatePrice(float $basePrice): float
    {
        return $basePrice * 2;
    }
}