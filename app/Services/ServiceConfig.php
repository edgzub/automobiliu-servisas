<?php

namespace App\Services;

class ServiceConfig
{
    private static $instance = null;
    private $config;

    private function __construct()
    {
        $this->config = [
            'vat_rate' => 0.21,
            'emergency_surcharge' => 1.5,
            'min_order_amount' => 10,
            'statuses' => [
                'laukiama',
                'vykdoma',
                'atlikta',
                'atsaukta'
            ],
            'working_hours' => [
                'start' => '08:00',
                'end' => '18:00'
            ]
        ];
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get($key)
    {
        return $this->config[$key] ?? null;
    }

    public function getStatuses()
    {
        return $this->config['statuses'];
    }

    public function calculatePriceWithVAT($price)
    {
        return $price * (1 + $this->config['vat_rate']);
    }
}