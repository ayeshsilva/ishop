<?php

namespace App\Service;

use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;

class StripeService
{
    public function __construct()
    {
        \Stripe\Stripe::setApiKey($_ENV["STRIPE_SET_API"]);
    }


    /**
     * @throws ApiErrorException
     */
    public function createPayment($amountTotal): string
    {
        // Create a PaymentIntent with amount and currency
        $paymentIntent = PaymentIntent::create([
            'amount' => $amountTotal*100,
            'currency' => 'eur',
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);

        return $paymentIntent->client_secret;
    }
}