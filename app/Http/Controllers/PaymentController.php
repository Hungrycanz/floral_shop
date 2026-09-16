<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function process(Request $request, Payment $payment, PaymentGatewayService $gateway)
    {
        $order = $payment->order;

        abort_unless(
            $order->user_id === $request->user()->id || $request->user()->isAdmin(),
            403,
        );

        $gateway->charge($payment);

        return back()->with('success', 'Payment processed successfully.');
    }
}
