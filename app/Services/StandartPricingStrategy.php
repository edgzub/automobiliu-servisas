<?php

namespace App\Services;

class StandardPricingStrategy implements PricingStrategy
{
    public function calculatePrice(float $basePrice): float
    {
        return $basePrice;
    }
}