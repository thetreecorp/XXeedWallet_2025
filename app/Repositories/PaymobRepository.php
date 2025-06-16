<?php

namespace App\Repositories;

use App\Http\Helpers\Common;
use App\Models\Currency;
use App\Models\CurrencyPaymentMethod;
use App\Models\Deposit;
use App\Models\PaymobPayment;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PaymobRepository
{
    private $methodData = null;

    public function __construct($methodData = null)
    {
        $this->methodData = $methodData;
    }

    public function getMethodData($referenceId){
        $payment = $this->getPaymentDetailsByRefId($referenceId);
        $currencyId = $payment->currency_id;
        $methodId = $payment->method_id;
        $currencyPaymentMethod = CurrencyPaymentMethod::where(['currency_id' => $currencyId, 'method_id' => $methodId])->where('activated_for', 'like', "%deposit%")->first(['method_data']);
        $this->methodData = json_decode($currencyPaymentMethod->method_data);
    }

    private function getPaymentDetailsByRefId($referenceId){
        return PaymobPayment::where('reference_id',$referenceId)->first();
    }
    public function getPayableLink($authSessionKey, $amount, $methodId, $currencyId, $currency, $paymobPayment, $type = 'card')
    {
        if($authSessionKey){
            Session::put('paymob', ['transInfo'=>['amount'=>$amount, 'totalAmount'=>$amount, 'payment_method_id'=>$methodId,'currency_id'=>$currencyId, 'currency'=>$currency]]);

            $referenceId = $this->getPaymobRefNo($currencyId,$methodId);

            $paymobPayment->type = $type;
            $paymobPayment->reference_id = $referenceId;
            $paymobPayment->save();

            $checkoutDataMethod = "getPaymob".ucfirst($type)."Data";
            $data = $this->$checkoutDataMethod([
                'amount'=>$amount,
                'reference_id'=>$referenceId,
                'type'=>$type,
                'currency'=>$currency,
                'item_name'=>'deposit',
                'item_description'=>'deposite of '.$amount.' '.$currency->code,
            ]);

            $checkoutMethod = "paymob".ucfirst($type)."Checkout";
            $url = $this->$checkoutMethod($authSessionKey, $data);

            $paymobPayment->payment_url = $url;
            $paymobPayment->save();

            return $url;
        }
    }

    private function getPaymobCardData($data){
        return array(
            'payment_link_image'=> '',
            'amount_cents'=>$data['amount']*100,
            'expires_at'=>Carbon::now()->addMinutes(360)->format('Y-m-d\TH:i:s'),
            'reference_id'=>$data['reference_id'],
            'payment_methods'=>$this->getPaymobIntegrationId($data['type']),
            'is_live'=> ($this->methodData->mode == 'sandbox') ? 'false' : 'true',
            'full_name'=> Auth::user()->first_name.' '.Auth::user()->last_name,
            'email'=>Auth::user()->email,
            'phone_number'=>Auth::user()->formattedPhone
        );
    }

    private function getPaymobWalletData($data){
        return [
            'amount'=>$data['amount']*100,
//            'currency'=>$data['currency']->code,
            'currency'=>'EGP',
            'payment_methods'=> [$this->getPaymobIntegrationId($data['type'])],
            'item'=>[
                [
                    'name'=>$data['item_name'],
                    'amount'=>$data['amount']*100,
                    'description'=>$data['item_description'],
                    'quantity'=>1
                ]
            ],
            'billing_data'=>[
                'first_name'=>Auth::user()->first_name,
                'last_name'=>Auth::user()->last_name,
                'phone_number'=>Auth::user()->formattedPhone
            ],
            'special_reference'=>$data['reference_id']
        ];
    }

    private function paymobCardCheckout($sessionKey, array $data){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config('paymob.PAYMENT_URL_CARD'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $data,
            CURLOPT_HTTPHEADER => array(
                'Authorization: '.$sessionKey
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $resCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if($resCode == 201){
            $res = json_decode($response);
            $this->updateTransactionReferenceId($data['reference_id'],$res->order);
            return $res->client_url;
        }

        return null;
    }

    private function paymobWalletCheckout($sessionKey, array $data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config('paymob.PAYMENT_URL_WALLET'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$sessionKey,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $resCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if($resCode == 201){
            $res = json_decode($response);

            $this->updateTransactionReferenceId($data['special_reference'],$res->id);

            return  str_replace(
                [':public_key', ':client_key'],
                [$this->methodData->public_key, $res->client_secret],
                config('paymob.PUBLIC_PAYMENT_URL_BY_WALLET')
            );
        }

        return null;
    }

    private function updateTransactionReferenceId($refId, $newTrxId)
    {
        $paymobPayment = $this->getPaymentDetailsByRefId($refId);
        $paymobPayment->transaction_id = $newTrxId;
        $paymobPayment->save();
        return $paymobPayment;
    }

    private function getPaymobIntegrationId($type)
    {
        if($type == 'card')
            return config('paymob.CARD_PAYMENT_INTEGRATION_ID');
        else if($type == 'wallet')
            return config('paymob.WALLET_PAYMENT_INTEGRATION_ID');
    }

    private function getPaymobRefNo($currencyId, $paymentMethodId)
    {
        $referenceId = str_pad($paymentMethodId, 3, '0', STR_PAD_RIGHT).str_pad($currencyId, 2, '0', STR_PAD_LEFT).date('ymd').rand(100,999);
        if(PaymobPayment::where('reference_id',$referenceId)->exists()){
            return $this->getPaymobRefNo($currencyId, $paymentMethodId);
        }
        return $referenceId;
    }

    public function getPaymobAuthKey(){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config('paymob.AUTH_URL'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "username": "'.$this->methodData->user_name.'",
                "password": "'.$this->methodData->password.'",
                "expiration": 999999999999
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $resCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if($resCode == 201){
            $res = json_decode($response);
            return $res->token;
        }

        return false;
    }
    public function verifyPayment($request)
    {
        $referenceNo = $request->merchant_order_id;
        $status = $request->success;
        $transactionId = $request->id;
        $amountCents = $request->amount_cents;

        $paymobPayment = $this->getPaymentDetailsByRefId($referenceNo);

        if($paymobPayment->payment_status == 'paid'){
            return redirect('deposit');
        }

        if(!$status){
            $paymobPayment->payment_status = 'failed';
            $paymobPayment->save();
        }

        if($status){
            DB::beginTransaction();

            try{
                if($authKey = $this->getPaymobAuthKey()){
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => config('paymob.PAYMENT_VERIFY_URL').$transactionId,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: '.$authKey
                        ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);
                    $resCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                    if($resCode == 200){
                        $res = json_decode($response);

                        $paymobPayment->payment_status = 'paid';
                        $paymobPayment->save();

                        $sessionValue      = session('paymob.transInfo');
                        $amount            = (double) $sessionValue['amount'];
                        $payment_method_id = Session::get('payment_method_id');
                        $currencyId        = (int) $sessionValue['currency_id'];

                        if($res->order->merchant_order_id ==$referenceNo && $res->amount_cents == $amountCents && $amount==$amountCents/100){
                            $user_id           = auth()->user()->id;
                            $wallet            = Wallet::where(['currency_id' => $currencyId, 'user_id' => $user_id])->first(['id', 'currency_id']);
                            if (empty($wallet)) {
                                $walletInstance = Wallet::createWallet($user_id, $currencyId);
                            }
                            $currencyId = isset($wallet->currency_id) ? $wallet->currency_id : $walletInstance->currency_id;
                            $currency = Currency::find($currencyId, ['id', 'code']);

                            $depositConfirm = Deposit::success($currencyId, $payment_method_id, $user_id, $sessionValue);

                            $data['transaction'] = session('paymob.transInfo');
                            //clearing session
                            session()->forget(['transaction', 'coinpaymentAmount', 'wallet_currency_id', 'method', 'payment_method_id', 'amount', 'publishable', 'transInfo', 'data']);
                            clearActionSession();
                            DB::commit();
                            return view('user.deposit.success', $depositConfirm);
                        }
                    }

                    return redirect('deposit');
                }
            }
            catch(\Exception $e){
                DB::rollBack();
                $helper = new Common();
                $helper->one_time_message('error', __('Sorry something went wrong!'));
                return redirect()->back();
            }

        }
    }
}
