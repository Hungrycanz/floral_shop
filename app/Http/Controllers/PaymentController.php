<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaymentGatewayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function process(Request $request, Payment $payment, PaymentGatewayService $gateway): RedirectResponse
    {
        abort_unless($request->user()->isAdmin(), 403);

        abort_unless($payment->order !== null, 404);

        $gateway->charge($payment);

        return back()->with('success', 'Payment processed successfully.');
    }
}
