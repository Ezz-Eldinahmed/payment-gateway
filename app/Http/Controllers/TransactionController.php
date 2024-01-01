<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Exception\CardException;
use Stripe\Stripe;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    private $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));;
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->stripe->paymentIntents->create([
                'amount' => 99 * 100,
                'currency' => 'usd',
                'payment_method' => $request->payment_method,
                'description' => 'Demo payment with stripe',
                'confirm' => true,
                'receipt_email' => $request->email
            ]);
        } catch (CardException $th) {
            throw new Exception("There was a problem processing your payment", 1);
        }

        return back()->withSuccess('Payment done.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    function charge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullName' => 'required',
            'cardNumber' => 'required',
            'month' => 'required',
            'year' => 'required',
            'cvv' => 'required'
        ]);

        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->errors()->first());
            return response()->redirectTo('/');
        }

        $token = $this->createToken($request);
        if (!empty($token['error'])) {
            $request->session()->flash('danger', $token['error']);
            return response()->redirectTo('/');
        }
        if (empty($token['id'])) {
            $request->session()->flash('danger', 'Payment failed.');
            return response()->redirectTo('/');
        }

        $charge = $this->createCharge($token['id'], 2000);
        if (!empty($charge) && $charge['status'] == 'succeeded') {
            $request->session()->flash('success', 'Payment completed.');
        } else {
            $request->session()->flash('danger', 'Payment failed.');
        }

        return response()->redirectTo('/');
    }

    private function createToken($cardData)
    {
        $token = null;
        try {
            $token = $this->stripe->tokens->create([
                'card' => [
                    'number' => $cardData['cardNumber'],
                    'exp_month' => $cardData['month'],
                    'exp_year' => $cardData['year'],
                    'cvc' => $cardData['cvv']
                ]
            ]);
        } catch (CardException $e) {
            $token['error'] = $e->getError()->message;
        } catch (Exception $e) {
            $token['error'] = $e->getMessage();
        }
        return $token;
    }

    private function createCharge($tokenId, $amount)
    {
        $charge = null;
        try {
            $charge = $this->stripe->charges->create([
                'amount' => $amount,
                'currency' => 'usd',
                'source' => $tokenId,
                'description' => 'My first payment'
            ]);
        } catch (Exception $e) {
            $charge['error'] = $e->getMessage();
        }
        return $charge;
    }
}
