<?php

/** @noinspection ALL */

namespace App\Http\Controllers;

use Stripe\Exception\CardException;
use App\Models\Transaction;
use Exception;
use App\Http\Requests\TransactionStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\StripeClient;

class TransactionController extends Controller
{
    public function checkout()
    {
        return 'something went wrong';
    }

    private $stripe;

    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient('sk_test_51HyGgPLfxWT4kkmEgV7XQptGezV13HWu4HuSq4sQM5dFDEa5AMs6EDPLyToHhSeyaikHqKUZ359KvvCvcf5mYXnA00mxUeXoMs');
    }

    public function store(TransactionStoreRequest $request)
    {
        // first method
        // Stripe::setApiKey('sk_test_51HyGgPLfxWT4kkmEgV7XQptGezV13HWu4HuSq4sQM5dFDEa5AMs6EDPLyToHhSeyaikHqKUZ359KvvCvcf5mYXnA00mxUeXoMs');

        // try {
        //     Charge::create([
        //         'amount' => 1000, // Amount in cents
        //         'currency' => 'usd',
        //         'source' => 'tok_mastercard',
        //         'description' => 'Test Payment',
        //     ]);

        //     return 'success';
        // } catch (\Exception $e) {
        //     return $e->getMessage();
        // }

        // used in create token
        // $token  = $stripe->tokens->create([
        //     'card' => [
        //         'number' => '4242424242424242',
        //         'exp_month' => 2,
        //         'exp_year' => 2023,
        //         'cvc' => '314',
        //     ],
        // ]);
        // tok_visa

        // second method

        // $charge = $this->stripe->charges->create([
        //     'amount' => 2000,
        //     'currency' => 'usd',
        //     'source' => 'tok_mastercard',
        //     'description' => 'My First Test Charge',
        // ]);

        // third method
        try {
            $this->stripe->paymentIntents->create([
                'amount' => $request->amount * 100,
                'currency' => 'usd',
                'payment_method' => 'pm_card_visa',
                'description' => 'Demo payment with stripe',
                'confirm' => true,
                'receipt_email' => 'ezz@ahmed.com',
                'return_url' => route('checkout')
            ]);

            return Transaction::create([
                'amount' => $request->amount * 100,
                'currency' => 'usd',
                'payment_method' => 'pm_card_visa',
                'description' => 'Demo payment with stripe',
                'receipt_email' => 'ezz@ahmed.com',
            ]);
        } catch (CardException $th) {
            throw new Exception("There was a problem processing your payment", 1);
        }
    }
}
