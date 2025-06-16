<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Common;
use App\Models\CurrencyPaymentMethod;
use App\Models\Parameter;
use App\Models\PaymobPayment;
use App\Services\Gateways\Gateway\GatewayHandler;
use App\Services\Gateways\Gateway\PaymentProcessor;
use App\Services\Gateways\Paymob\PaymobRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GatewayController extends Controller
{
    protected $helper;
    public $successStatus = 200;
    public $unprocessedStatus = 422;

    public function __construct()
    {
        $this->helper = new Common();

        if (request('gateway')) {
            GatewayHandler::gatewayIssue(request("gateway"));
        }

    }

    /**
     * Method pay
     * initiate payment processor
     * return payment gateway view
     *
     * @return void
     */
    public function pay(PaymentProcessor $processor)
    {
        try {

            if (!isset(request()->params)) {
                throw new Exception(__('Payment parameter isn\'t available'));
            }

            $data = getPaymentParam(request()->params);

            $data['params'] = request()->params;

            $this->validateData($data);

            return $processor->initiateGateway($data);

        } catch (Exception $exception) {
            $this->helper->one_time_message('error', __($exception->getMessage()));
            return redirect('payment/fail');
        }
    }

    public function redirectToPaymobLink(Request $request){
        $currencyId = $request->currency_id;
        $methodId = $request->method_id;
        $paymobPaymentId = $request->paymob_payment_id;
        $type = $request->paymob_payment_type;

        $paymobPayment = PaymobPayment::where(['currency_id'=>$currencyId, 'method_id'=>$methodId, 'id'=>$paymobPaymentId])->first();

        if(!$paymobPayment){
            $this->helper->one_time_message('error', __('Sorry we can not process this payment! No payment reference found.'));
            return redirect('payment/fail');
        }

        $amount = $paymobPayment->amount;
        $currency = $paymobPayment->currency;

        $currencyPaymentMethod = CurrencyPaymentMethod::where(['currency_id' => $paymobPayment->currency_id, 'method_id' => $paymobPayment->method_id])->where('activated_for', 'like', "%deposit%")->first(['method_data']);
        $methodData = json_decode($currencyPaymentMethod->method_data);

        $paymobRepository = new PaymobRepository($methodData);
        $authSessionKey = $methodData->api_key;
        $url = $paymobRepository->getPayableLink($authSessionKey, $amount, $methodId, $currencyId, $currency, $paymobPayment, $type, $request->params);

        if($url)
            return redirect($url);
        else
        {
            $this->helper->one_time_message('error', __('Something went wrong with this payment method!'));
        }
        return redirect('payment/fail');
    }

    public function paymobVerify(Request $request, PaymobRepository $paymobRepository){
        $paymobRepository->getMethodData($request->merchant_order_id);
        return $paymobRepository->verifyPayment($request);
    }

    /**
     * Method confirmPayment
     *
     * @param Request $request
     * @param PaymentProcessor
     *
     * Execute the payment to the gateway
     *
     * @return void
     */
    public function confirmPayment(Request $request, PaymentProcessor $processor)
    {
        try {
            $processor->setPaymentType($request->payment_type);

            $response = $processor->pay(array_merge($request->all(), ['total_amount' => request()->amount]));

            if(isset($response['redirect']) && $response['redirect'] ) {
                return redirect($response['redirect_url']);
            }

            return response()->json([
                'data' => $response,
                'status' => $this->successStatus,
            ]);

        } catch (Exception $exception) {

            if (!$request->ajax()) {
                $this->helper->one_time_message('error', $exception->getMessage());
                return redirect('payment/fail');
            }

            return response()->json([
                'status' => $this->unprocessedStatus,
                'message' => $exception->getMessage(),
            ]);
        }

    }

    /**
     * Validate data against rules
     *
     * @param array $data
     *
     * @return array
     *
     * @throws Exception
     */
    public function validateData($data)
    {
        // Thess fields are required to implement a payment gateway
        $rules = [
            'currency_id' => 'required',
            'currencyCode' => 'required',
            'total' => 'required', // After calculating all fees (The amount which will be deduct from gateway account)
            'transaction_type' => 'required', // Deposit, Payment_Sent & other on demand module transaction type
            'payment_type' => 'required', // To calculate fees limit need to provide payment type, if transaction type is Deposit payment type will be "deposit"
            'redirectUrl' => 'required', // After payment done via gateway will execute the url to process the transaction
            'success_url' => 'required', // The Page which will show as success page
            'gateway' => 'required', // Payment method name
            'payment_method' => 'required', // Payment Method id
            'banks' => 'required_if:payment_method,' . Bank,
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            //  Return first error message
            throw new Exception($errors[0]);
        }

        return $validator->validated();
    }

    public function verify(Request $request, PaymentProcessor $processor)
    {
        try {

            $response = $processor->verify($request);

            Parameter::where('unique_code', $request->params)->delete();

            setPaymentData($response);

            return redirect($response['success_url']);

        } catch (Exception $e) {
            $this->helper->one_time_message('error', $e->getMessage());
            return redirect()->to('payment/fail');
        }
    }

    public function cancelPayment(Request $request, PaymentProcessor $processor)
    {
        try {
            $processor->callback($request);
            $this->helper->one_time_message('error', __('You have cancelled your payment'));
        } catch (Exception $e) {
            $this->helper->one_time_message('error', $e->getMessage());
        }

        return redirect()->to('payment/fail');

    }

}
