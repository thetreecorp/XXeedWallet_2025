<?php

namespace App\Http\Controllers\Users;

use App\Exceptions\Api\V2\AmountLimitException;
use Exception;
use App\Services\DepositMoneyService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Helpers\Common;
use App\Http\Requests\Api\V2\DepositMoney\ValidateDepositRequest;
use App\Models\{Deposit,
    Transaction,
    Currency,
    Bank,
    Wallet
};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DepositController extends Controller
{
    protected $helper;
    protected $service;

    public function __construct(DepositMoneyService $service)
    {
        $this->helper  = new Common();
        $this->service  = $service;
    }

    public function create()
    {
        setActionSession();

        if (route('user.deposit.confirm') !== url()->previous()) {
            if (!empty(session('paymentData'))) {
                session()->forget('paymentData');
            }
        }

        $response  = $this->service->getSelfCurrencies();

        $data = [
            'menu' => 'deposit',
            'icon' => 'university',
            'content_title' => 'Deposit',
            'defaultWallet' => $response['default'],
            'activeCurrencyList' => $response['currencies']
        ];

        return view('user.deposit.create', $data);
    }

    public function depositConfirm(ValidateDepositRequest $request)
    {
        try {
            $transInfo  = $this->service->validateDepositable(
                $request->currency_id,
                $request->amount,
                $request->payment_method
            );
            $keme0 = config('constants.options.keme0') ? config('constants.options.keme0'):  'keme0';
            $keme100 = config('constants.options.keme100') ? config('constants.options.keme100') : 'keme100';

            // update if kemecoin0 and kemecoin 100
            if($transInfo && ($transInfo['currencySymbol'] == $keme0 || $transInfo['currencySymbol'] == $keme100)) {
                $transInfo['kemecoinSymbol'] =  $transInfo['currencyCode'];
                // Update currency and total
                $transInfo['currencyCode'] = "USD";
                $transInfo['currencySymbol'] = "$";
                // $transInfo['amount'] = "10";
                // $transInfo['totalAmount'] = 10.0;
                // $transInfo['currencyId'] = 8;

                // $transInfo['formattedAmount'] = "10";
                // $transInfo['formattedTotalAmount'] = "10";
                // $transInfo['currencyCode'] = "USD";
                // $transInfo['currencySymbol'] = "$";
                $transInfo['amount'] = (string)((int)$request->amount * 0.01);
                $transInfo['totalAmount'] = (float)((int)$request->amount * 0.01);

                $transInfo['formattedAmount'] = (string)((int)$request->amount * 0.01);
                $transInfo['kemecoin'] = "kemecoin";
                $transInfo['kemecoinAmount'] = $request->amount;
                
                $transInfo['formattedTotalAmount'] = (string)((int)$request->amount * 0.01);
            }

            //dd($transInfo);


            setPaymentData($transInfo);
            return view('user.deposit.confirm', $transInfo);

        } catch (AmountLimitException $e) {
            $this->helper->one_time_message('error', __( $e->getMessage() ));
            return redirect('deposit');
        }

    }

    /**
     * Method depositGateway
     *
     * @param Request $request
     *
     * Set payment data
     *
     * Generate Payment url, redirect to payment page
     *
     */
    public function depositGateway(Request $request)
    {
        actionSessionCheck();

        $data = getPaymentData();

        //dd( $data);

        // These are the mandatory field for dynamic gateway payment.
        $paymentData = [
            'currency_id' => $data['currencyId'],
            'total' => $data['totalAmount'],
            'transaction_type' => Deposit,
            'payment_type' => 'deposit',
            'method' => $data['payment_method'],
            'redirectUrl' => route('deposit.complete'),
            'success_url' => route('user.deposit.success'),
            'cancel_url' => url('deposit'),
            'gateway' => $data['paymentMethodAlias'],
            'user_id' => Auth::id(),
            'uuid' => unique_code()
        ];

        if ($data['payment_method'] == Bank) {
            $paymentData['banks'] = getBankList($data['currencyId'], 'deposit');
        }

        $data = array_merge($data, $paymentData);

        setPaymentData($data);

       // dd( $data);

        return redirect(gatewayPaymentUrl($data));

    }

    /**
     * Method depositComplete
     *
     * @param Request $request [parameter from gateway response]
     *
     * After complete the payment via gateway will return here
     *
     * Process the transaction
     *
     */
    public function depositComplete(Request $request)
    {

        try {

            // dd(request()->params);
            // exit();
            $data = getPaymentParam(request()->params);

            isGatewayValidMethod($data['payment_method']);

            $details = [];

            if ($data['payment_method'] == Bank ) {

                if (isset($request->bank, $request->attachment)) {
                    $details = [
                        'bank' => $request->bank,
                        'attachment' => $request->attachment
                    ];
                }
            }

            $depositResponse = $this->service->processPaymentConfirmation(
                $data['currency_id'],
                $data['payment_method'],
                $data['totalAmount'],
                $data['amount'],
                $data['user_id'],
                $data['uuid'],
                $details
            );

            $data ['transaction_id'] = $depositResponse['transaction']->id;

            clearActionSession();

            if (isset(request()->execute) && (request()->execute == 'api')) {
                return  $data['transaction_id'];
            }



            setPaymentData($data);

            return redirect()->route('user.deposit.success');

        } catch (Exception $e) {

            if (isset(request()->execute) && (request()->execute == 'api')) {
                return [
                    'status' => '401',
                    'message' => $e->getMessage()
                ];
            }

            $this->helper->one_time_message('error', __( $e->getMessage() ));
            return redirect('deposit');
        }

    }

    /**
     * Method depositSuccess
     *
     * Show deposit success page
     *
     * @return view
     */
    public function depositSuccess()
    {
        try {
            $data = getPaymentData();
            
            if(isset($data['kemecoin'])) {

                if(isset($data['transaction_id'])) {
                    $t = Transaction::find($data['transaction_id']);
                    if($t && $t->status == 'Success') {

                        // update row when buy kemecoin
                        $deposit_id = $t->transaction_reference_id;
                        Deposit::where('id', $deposit_id)->update(['amount' => floatval($data['kemecoinAmount'])]);
                        Transaction::where('id', $data['transaction_id'])->update(['total' => floatval($data['kemecoinAmount']), 'subtotal' => floatval($data['kemecoinAmount'])]);
                        Wallet::incrementWalletBalance($data['user_id'], $data['currencyId'], floatval($data['kemecoinAmount']));
                        Wallet::deductAmountFromWallet($data['user_id'], $data['currencyId'], floatval($data['totalAmount']));
                    }
                }
            }
            
            return view('user.deposit.success', $data);
        } catch (Exception $e) {
            $this->helper->one_time_message('error', $e->getMessage());
           // return redirect('deposit');
        }

    }



    public function getPaymentMethods(Request $request)
    {
        $data = [];
        $currencyType = Currency::where('id', $request->currency_id)->value('type');
        $data['paymentMethods'] = $this->service->getPaymentMethods($request->currency_id, $currencyType, $request->transaction_type_id, 'web');
        $data['preference'] = ($currencyType == 'fiat') ? preference('decimal_format_amount', 2) : preference('decimal_format_amount_crypto', 8);
        return response()->json(['success' => $data]);
    }


    public function getDepositFeesLimit(Request $request)
    {
        try {
            $data = $this->service->validateDepositable($request->currency_id, $request->amount, $request->payment_method_id);
            $data['status'] = '200';
        } catch (Exception $e) {
            $data = [
                'message' => __($e->getMessage()),
                'status' => '401'
            ];
        }
        return response()->json(['success' => $data]);
    }


    public function getBankDetailOnChange(Request $request)
    {
        $bank = Bank::with('file:id,filename')->where(['id' => $request->bank])->first(['bank_name', 'account_name', 'account_number', 'file_id']);
        if ($bank){
            $data['status'] = true;
            $data['bank']   = $bank;

            if (!empty($bank->file_id))
            {
                $data['bank_logo'] = $bank->file?->filename;
            }
        } else{
            $data['status'] = false;
            $data['bank']   = __('Bank Not FOund');
        }
        return $data;
    }

    public function depositPrintPdf($trans_id)
    {
        $data['transactionDetails'] = Transaction::with(['user','payment_method:id,name', 'currency:id,symbol,code'])
            ->where(['id' => $trans_id])
            ->first(['user_id', 'currency_id', 'payment_method_id', 'uuid', 'transaction_type_id', 'charge_percentage', 'charge_fixed', 'subtotal', 'total', 'status', 'created_at']);

        generatePDF('user.deposit.deposit-pdf', 'deposit_', $data);
    }


    public function coinPaymentSummary()
    {
        if (Session::has('transactionDetails') && Session::has('transactionInfo')) {
            $transactionDetails = Session::get('transactionDetails');
            $transactionInfo = Session::get('transactionInfo');
            $gateway = 'Coinpayments';

            return view('gateways.coinpayment_summery', compact('transactionDetails', 'transactionInfo', 'gateway'));
        }

        // Redirect to the deposit page if any of the sessions is not available
        $this->helper->one_time_message('error', __('Coinpayment Session has been ended'));
        return redirect()->route('home');
    }


}
