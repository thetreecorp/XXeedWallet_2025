<?php

namespace App\Services\Gateways\Paymob;

use App\Models\PaymobPayment;
use Exception;
use App\Services\Gateways\Gateway\Exceptions\{
    GatewayInitializeFailedException,
};
use App\Services\Gateways\Gateway\PaymentProcessor;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaymobProcessor extends PaymentProcessor
{
    protected $paymob;
    protected $paymobServiceRepository;

    protected function boot($data)
    {
        $this->data = $data;

        $this->paymentCurrency();

        $this->paymob = $this->paymentMethodCredentials();

        $this->paymobServiceRepository = new PaymobRepository($this->paymob);

        if (!isset($this->paymob->user_name) || !isset($this->paymob->password) || !isset($this->paymob->api_key) || !isset($this->paymob->public_key)) {
            throw new GatewayInitializeFailedException(__("Paymob initialize failed."));
        }
    }

    protected function pay(array $data) : array
    {
        $data['payment_method_id'] = Paymob;

        $this->boot($data);

        return [
            'status' => true,
            'message' => __('success')
        ];
    }

    public function gateway(): string
    {
        return "paymob";
    }

    public function getPaymobAuthKey()
    {
        return $this->paymobServiceRepository->getPaymobAuthKey();
    }

    public function initiateGateway($data){


        if(!Auth::user()->first_name ||
            !Auth::user()->last_name ||
            !Auth::user()->formattedPhone){
            throw new Exception("Your first name, last name and phone number is required to proceed. Go to profile and set all required information.");
        }

        $this->setPaymentType($data['payment_type']);

        $paymobPayment = PaymobPayment::create([
            'currency_id' => $data['currency_id'],
            'method_id' => $data['method'],
            'amount' => $data['totalAmount']
        ]);

        $data["paymob_payment_id"] = $paymobPayment->id;

        $paymobTypes = config('paymob.PAYMENT_TYPES');
        $data['types'] = $paymobTypes;
        return view(('gateways.' . $this->gateway()), $data);
    }

    public function createPaymentIntent($amount, $currency)
    {

    }
}
