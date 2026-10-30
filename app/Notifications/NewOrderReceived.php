<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Sent to a merchant when a customer order contains one or more of
 * their spare parts. One notification per merchant per order — never
 * duplicated across items.
 */
class NewOrderReceived extends Notification
{
    use Queueable;

    /**
     * @param  array<int, array{name:string, quantity:int, subtotal:float}>  $products
     */
    public function __construct(
        public Order $order,
        public array $products,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'            => 'new_order_received',
            'order_id'        => $this->order->id,
            'order_reference' => $this->order->order_reference,
            'customer'        => $this->order->name,
            'products'        => $this->products,
            'item_count'      => count($this->products),
            'url'             => route('account.merchant-orders.index'),
        ];
    }
}
