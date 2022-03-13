<?php

namespace Mekachonjo\Payment\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Mekachonjo\Payment\Facades\Chonjopay;

class PaymentTypeMiddleware
{
    /**
     * Check ChonjoPay update coming from valid type of payment.
     *
     * @param Request $request
     * @param Closure $next
     * @param string ...$paymentTypes
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string ...$paymentTypes)
    {
        if (in_array(Chonjopay::getPaymentType(), $paymentTypes)) {
            return $next($request);
        }

        return false;
    }
}
