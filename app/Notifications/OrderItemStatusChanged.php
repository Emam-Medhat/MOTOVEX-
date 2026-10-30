<?php

namespace App\Notifications;

use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Sent to the customer whenever one of their order items changes
 * fulfillment status (accepted, preparing, shipped, delivered, …).
 */
class OrderItemStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public OrderItem $item)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'            => 'order_item_status_changed',
            'order_item_id'   => $this->item->id,
            'order_id'        => $this->item->order_id,
            'order_reference' => $this->item->order?->order_reference,
            'product'         => $this->item->displayName(),
            'status'          => $this->item->status,
            'status_label'    => OrderItem::$merchantStatuses[$this->item->status] ?? ucfirst($this->item->status),
            'rejection_reason'=> $this->item->rejection_reason,
            'url'             => $this->item->order
                ? route('account.orders.show', $this->item->order)
                : route('account.orders'),
        ];
    }
}
