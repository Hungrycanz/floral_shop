<?php

namespace App\Services;

use App\Models\Payment;

class PaymentGatewayService
{
    public function charge(Payment $payment): Payment
    {
        if ($payment->status === 'completed') {
            return $payment;
        }

        $payment->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        return $payment->fresh();
    }

    public function fail(Payment $payment): Payment
    {
        if ($payment->status === 'completed') {
            return $payment;
        }

        $payment->update(['status' => 'failed']);

        return $payment->fresh();
    }
}
