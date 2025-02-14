<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class OrderObserver
{
    public function created(Order $order)
    {
        // Siųsti patvirtinimo el. laišką
        $this->sendOrderNotification($order, 'created');
    }

    public function updated(Order $order)
    {
        if ($order->isDirty('statusas')) {
            $this->sendOrderNotification($order, 'status_changed');
        }
    }

    private function sendOrderNotification(Order $order, string $type)
    {
        $client = $order->vehicle->client;
        $message = $this->getNotificationMessage($order, $type);
        
        // Čia būtų realus el. pašto siuntimas
        \Log::info("Sending notification to {$client->el_pastas}: {$message}");
    }

    private function getNotificationMessage(Order $order, string $type): string
    {
        switch ($type) {
            case 'created':
                return "Jūsų užsakymas #{$order->id} sėkmingai priimtas.";
            case 'status_changed':
                return "Jūsų užsakymo #{$order->id} statusas pakeistas į: {$order->statusas}";
            default:
                return "Užsakymo #{$order->id} būsena atnaujinta.";
        }
    }
}