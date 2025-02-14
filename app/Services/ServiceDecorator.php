<?php

namespace App\Services;

use App\Models\Service;

abstract class ServiceDecorator
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    abstract public function getPrice(): float;
    abstract public function getDescription(): string;
}

class UrgentServiceDecorator extends ServiceDecorator
{
    public function getPrice(): float
    {
        return $this->service->kaina * 1.5;
    }

    public function getDescription(): string
    {
        return $this->service->aprasymas . ' (Skubus aptarnavimas)';
    }
}

class WeekendServiceDecorator extends ServiceDecorator
{
    public function getPrice(): float
    {
        return $this->service->kaina * 2;
    }

    public function getDescription(): string
    {
        return $this->service->aprasymas . ' (Savaitgalio aptarnavimas)';
    }
}