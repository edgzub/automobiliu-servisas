<?php

namespace App\Services;

interface PricingStrategy
{
    public function calculatePrice(float $basePrice): float;
}

class StandardPricingStrategy implements PricingStrategy
{
    public function calculatePrice(float $basePrice): float
    {
        return $basePrice;
    }
}

class UrgentPricingStrategy implements PricingStrategy
{
    public function calculatePrice(float $basePrice): float
    {
        return $basePrice * ServiceConfig::getInstance()->get('emergency_surcharge');
    }
}

class WeekendPricingStrategy implements PricingStrategy
{
    public function calculatePrice(float $basePrice): float
    {
        return $basePrice * 2;
    }
}