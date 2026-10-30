<?php

namespace App\Notifications;

use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Sent to admins when a merchant rejects an order item so the team
 * can review and handle the dispute.
 */
class RejectedOrderItemAlert extends Notification
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
            'type'            => 'rejected_order_item',
            'order_item_id'   => $this->item->id,
            'order_id'        => $this->item->order_id,
            'order_reference' => $this->item->order?->order_reference,
            'product'         => $this->item->displayName(),
            'merchant'        => $this->item->merchant?->name,
            'reason'          => $this->item->rejection_reason,
            'url'             => $this->item->order_id
                ? route('admin.sales.show', $this->item->order_id)
                : route('admin.sales.index'),
        ];
    }
}
