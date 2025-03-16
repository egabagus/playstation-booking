<?php

namespace App\Services\Transactions;

use App\Models\Transaction;
use Midtrans\Config;
use Midtrans\Snap;
use Throwable;

class TransactionService
{
    public static function pay($booking, $payload)
    {
        try {
            Config::$serverKey    = config('services.midtrans.serverKey');
            Config::$isProduction = config('services.midtrans.isProduction');
            Config::$isSanitized  = config('services.midtrans.isSanitized');
            Config::$is3ds        = config('services.midtrans.is3ds');

            $data = [
                'transaction_details' => [
                    'order_id'     => $booking->trx_id,
                    'gross_amount' => $payload['amount'],
                ],
                'customer_details' => [
                    'first_name' => $payload['customer_name'],
                    'email'      => $payload['customer_email'],
                ],
                'item_details' => [
                    [
                        'id'            => $payload['product']->id,
                        'price'         => $payload['amount'],
                        'quantity'      => 1,
                        'name'          => $payload['product']->name,
                        'brand'         => 'Shop',
                        'category'      => 'Shop',
                        'merchant_name' => 'Midtrans',
                    ],
                ],
            ];


            $snapToken = Snap::getSnapToken($data);

            $trx = Transaction::find($booking->id)->update([
                'snap_token' => $snapToken
            ]);

            return $snapToken;
        } catch (Throwable $e) {
            dd('Error:', $e->getMessage());
        }
    }
}
