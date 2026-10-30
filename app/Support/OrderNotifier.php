<?php

namespace App\Support;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Notifications\NewOrderReceived;
use App\Notifications\OrderItemStatusChanged;
use App\Notifications\RejectedOrderItemAlert;
use Illuminate\Support\Facades\Notification;

/**
 * Centralises order-related notifications so the same event never
 * produces duplicate notifications from different call sites.
 */
class OrderNotifier
{
    /**
     * Notify every merchant that owns at least one item in the order.
     * One notification per merchant, listing only that merchant's items.
     */
    public static function notifyMerchants(Order $order): void
    {
        $items = $order->items()
            ->whereNotNull('merchant_id')
            ->get()
            ->groupBy('merchant_id');

        foreach ($items as $merchantId => $merchantItems) {
            $merchant = User::find($merchantId);
            if (! $merchant) {
                continue;
            }

            $products = $merchantItems->map(fn (OrderItem $item) => [
                'name'     => $item->displayName(),
                'quantity' => (int) $item->quantity,
                'subtotal' => (float) $item->subtotal,
            ])->values()->all();

            $merchant->notify(new NewOrderReceived($order, $products));
        }
    }

    /**
     * Notify the customer of a fulfillment status change, and alert
     * admins when an item has been rejected.
     */
    public static function notifyStatusChange(OrderItem $item): void
    {
        $item->loadMissing(['order', 'merchant']);

        $order = $item->order;
        if ($order) {
            $customer = $order->user
                ?? User::where('email', $order->email)->first();

            $customer?->notify(new OrderItemStatusChanged($item));
        }

        if ($item->status === OrderItem::STATUS_REJECTED) {
            $admins = User::whereHas('roles', fn ($q) => $q->where('slug', 'admin'))->get();

            if ($admins->isNotEmpty()) {
                Notification::send($admins, new RejectedOrderItemAlert($item));
            }
        }
    }
}
