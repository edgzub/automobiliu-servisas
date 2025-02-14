<?php

namespace App\Services;

class UrgentPricingStrategy implements PricingStrategy
{
    public function calculatePrice(float $basePrice): float
    {
        return $basePrice * 1.5;
    }
}