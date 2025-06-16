<?php

/**
 * @package CommonController
 * @author tehcvillage <support@techvill.org>
 * @contributor Md Abdur Rahaman <[abdur.techvill@gmail.com]>
 * @created 30-11-2022
 */

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\User\UserLoginResource;
use App\Exceptions\Api\V2\LoginException;
use App\Http\Requests\UserLoginRequest;
use App\Services\AuthService;
use Carbon\Carbon;
use DB, Validator, Auth, Exception;
use Intervention\Image\Facades\Image;
use App\Models\Currency;
use Illuminate\Support\Str;
use App\Models\{
    ActivityLog,
    UserDetail,
    RoleUser,
    CryptoProvider,
    VerifyUser,
    QrCode,
    Role,
    Wallet,
    MerchantPayment,
    MerchantGroup,
    Merchant,
};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CommonController extends Controller
{
    /**
     * User Login
     * @param UserLoginRequest $request
     * @param AuthService $service
     * @return JsonResponse
     * @throws LoginException
    */

    protected $user;


    public function __construct()
    {
        $this->user   = new User();
       
    }

    public function createCurrency(Request $request)
    {
 
        try {
            
            if ($request->isMethod('post')) {
                
                

                $validator =  Validator::make($request->all(), [
                    'name' => 'required',
                    'code' => 'required',
                    'symbol' => 'required',
                    'type' => 'required',
                    'rate' => $request->type == 'fiat' ? 'required|numeric|min:0.0001' : '',
                    'status' => 'required',
    
                ]);
    
                if ($validator->fails()) {
                    return response()->json([
                        "error" => 'validation_error',
                        "message" => $validator->errors(),
                    ], 422);
                }
                
                $currency = $this->checkCurrency($request->symbol);
                
                if($currency) {
                    $currency->name = $request->name;
                    $currency->code = $request->code;
                    $currency->symbol = $request->symbol;
                    $currency->type = $request->type == 'fiat' ? 'fiat' : 'crypto';
                    $currency->rate =  0;
                    $currency->address = $request->type == 'crypto' ?  $request->address : '';
                    $currency->exchange_from = $request->exchange_from ? $request->exchange_from : '0';
                    $currency->status = $request->status == 'Active' ? 'Active' : 'Inactive';
                    $currency->default = '0';
                    $currency->token_icon = $request->token_icon ? $request->token_icon  : '';
                    $currency->save();
    
                    $data['message'] = "Currency updated successfully";
                    $data['status'] = 200;
                    
                    return response()->json($data, 200);
                }
                else {
                    $currency = new Currency();
                    $currency->name = $request->name;
                    $currency->code = $request->code;
                    $currency->symbol = $request->symbol;
                    $currency->type = $request->type == 'fiat' ? 'fiat' : 'crypto';
                    $currency->rate = $request->type == 'fiat' ? $request->rate : 0;
                    $currency->address = $request->type == 'crypto' ?  $request->address : '';
                    $currency->exchange_from = $request->exchange_from ? $request->exchange_from : '0';
                    $currency->status = $request->status == 'Active' ? 'Active' : 'Inactive';
                    $currency->default = '0';
                    $currency->token_icon = $request->token_icon ? $request->token_icon  : '';
                    $currency->save();
    
                    $data['message'] = "Currency created successfully";
                    $data['status'] = 1;
                    $data['code'] = 200;
                    
                    return response()->json($data, 200);
                }
               
            }
        } 
        catch (Exception $e) {
            return response()->json([
                'status'         => 0,
                'code' => 404,
                'message' => $e->getMessage(),
            ]);
        }
    }
    
    // Get token
    public function checkCurrency($symbol) {
        $check_currency = Currency::where('symbol', $symbol)->first();
        if($check_currency)
            return $check_currency;
        else
            return 0;
    }

    // Update currency
    public function updateCurrency(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                
                $currency = $this->checkCurrency($request->symbol);
                
                if($currency) {
                
                    $currency->name = $request->name;
                    $currency->code = $request->code;
                    $currency->symbol = $request->symbol;
                    $currency->type = $request->type == 'fiat' ? 'fiat' : 'crypto';
                    $currency->rate = $request->type == 'fiat' ? $request->rate : 0;
                    $currency->address = $request->type == 'crypto' ?  $request->address : '';
                    $currency->exchange_from = $request->exchange_from ? $request->exchange_from : '0';
                    $currency->status = $request->status == 'Active' ? 'Active' : 'Inactive';
                    $currency->default = '0';
                    $currency->token_icon = $request->token_icon ? $request->token_icon  : '';
                    $currency->save();
    
                    $data['message'] = "Currency updated successfully";
                    $data['status'] = 200;
                    
                    return response()->json($data, 200);
                }
                else {
                    $data['message'] = "Currency updated successfully";
                    $data['status'] = 1;
                    $data['code'] = 200;
                    
                    return response()->json($data, 200);
                }
            }
        } 
        catch (Exception $e) {
            return response()->json([
                'status'         => 0,
                'code' => 404,
                'message' => $e->getMessage(),
            ]);
        }
    }
    
    // delete currency
    public function deleteCurrency(Request $request) {
        try { 
            Currency::where('symbol', $request->symbol)->delete();
            
            $data['message'] = "Currency deleted successfully";
            $data['status'] = 1;
            $data['code'] = 200;
            return response()->json($data, 200);
        }
        catch (Exception $e) {
            return response()->json([
                'status'         => 0,
                'code' => 404,
                'message' => $e->getMessage(),
            ]);
        }
    }
    
    // Create user api

    public function createUser(Request $request)
    {
 
        try {
            
            //dd($request->isMethod('post'));exit();

            if ($request->isMethod('post')) {
        
                $validator =  Validator::make($request->all(), [
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                    'password' => 'required',
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'type' => 'required',
                ]);


                if ($validator->fails()) {
                    return response()->json([
                        "error" => 'validation_error',
                        "message" => $validator->errors(),
                    ], 422);
                }
                
                // $user = new User();
                // $user->email = $request->email;
                // $user->password = Hash::make($request->password);
                // $user->first_name = $request->first_name;
                // $user->last_name = $request->last_name;
                // $user->status = 'Active';

                

                // $user->save();

                // // Assign user type and role to new user
                // RoleUser::insert(['user_id' => $user->id, 'role_id' => $user->role_id, 'user_type' => 'User']);
                
               

                DB::beginTransaction();
                
                $user = $this->user->createNewUser($request, 'user');

                // Assign user type and role to new user
                RoleUser::insert(['user_id' => $user->id, 'role_id' => $user->role_id, 'user_type' => 'User']);

                // Create user detail
                $user->createUserDetail($user->id);

                // Create user's default wallet
                $user->createUserDefaultWallet($user->id, settings('default_currency'));

                // Create wallets that are allowed by admin
                if (settings('allowed_wallets') != 'none') {
                    $user->createUserAllowedWallets($user->id, settings('allowed_wallets'));
                }
                
                // QR Code
                QrCode::createUserQrCode($user);
                
                DB::commit();
                
                
                $merchant = 0;
                
                // create merchant
                try {
                   // DB::beginTransaction();
        
                    $picture  = $request->logo;
                    $fileName = null;
        
                    if (isset($picture)) {
                        $response = uploadImage($picture, public_path("/uploads/merchant/"),'100*80', null, '70*70');
        
                        if ($response['status'] === true) {
                            $fileName = $response['file_name'];
                        } else {
                            DB::rollBack();
                            $data['message'] = "Image upload fail";
                            $data['status'] = 0;
                            $data['code'] = 400;
                            
                            return response()->json($data, 200);
                        }
                    }
        
                    $merchantGroup               = MerchantGroup::where(['is_default' => 'Yes'])->select('id', 'fee')->first();
                    $merchant                    = new Merchant();
                    $merchant->user_id           = $user->id;
                    $merchant->currency_id       = $request->currency_id;
                    $merchant->merchant_group_id = isset($merchantGroup) ? $merchantGroup->id : null;
                    $merchant->business_name     = $request->business_name;
                    $merchant->site_url          = $request->site_url;
                    $uuid                        = unique_code();
                    $merchant->merchant_uuid     = $uuid;
                    $merchant->type              = $request->merchant_type;
                    $merchant->note              = $request->note;
                    $merchant->logo              = $fileName != null ? $fileName : '';
                    $merchant->fee               = isset($merchantGroup) ? $merchantGroup->fee : 0.00;
                    $merchant->status               = 'Approved';
        
                    if (module('WithdrawalApi') && isActive('WithdrawalApi')) {
                        $merchant->withdrawal_approval = $request->withdrawal_approval == 'on' ? 'Yes' : 'No';
                    }
        
                   $status = $merchant->save();
                   
                    
                    $client_id = Str::random(30);
                    $client_secret = Str::random(100);
                    if (strtolower($request->merchant_type) == 'express') {
                        try {
                            $merchantAppInfo = $merchant->appInfo()->create([
                                'client_id'     => $client_id,
                                'client_secret' => $client_secret,
                            ]);
                        } catch (Exception $ex) {
                         
                            $data['message'] = "Client id must be unique. Please try again!";
                            $data['status'] = 0;
                            $data['code'] = 400;
                            
                            return response()->json($data, 200);
                        }
        
                        $request->request->add([
                            'merchantId' => $merchant->id, 
                            'merchantDefaultCurrencyId' => $merchant->currency_id,
                            'clientId' => $merchantAppInfo->client_id
                        ]);
        
                        $this->generateOrUpdateExpressMerchantQrCode($request);
                    }
        
                    
                }
                catch (Exception $e)
                {
                    //DB::rollBack();

                    $data['message'] = $e->getMessage();
                    $data['status'] = 0;
                    $data['code'] = 400;
                    
                    return response()->json($data, 200);
                }
                
                
                $data['message'] = "User created successfully";
                $data['status'] = 1;
                $data['code'] = 200;
                $data['client_id'] = $client_id;
                $data['client_secret'] = $client_secret;
                
                return response()->json($data, 200);
               
            }
        } 
        catch (Exception $e) {
            
            //dd($e);
        
            return response()->json([
                'status' => 0,
                'code' => 404,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function generateOrUpdateExpressMerchantQrCode(Request $request) 
    {
        $qrCode = QrCode::where(['object_id' => $request->merchantId, 'object_type' => 'express_merchant', 'status' => 'Active'])->first(['id', 'secret']);
        $merchantCurrency = Currency::where('id', $request->merchantDefaultCurrencyId)->first(['code']);

        if (!empty($qrCode)) {
            $qrCode->status = 'Inactive';
            $qrCode->save();
        }


        $secretCode = convert_string('encrypt', 'express_merchant' . '-' . $request->merchantId . '-' . $merchantCurrency->code . '-' . $request->clientId . Str::random(6));

        $imageName = time() . '.' . 'jpg';

        $createMerchantQrCode = new QrCode();
        $createMerchantQrCode->object_id   = $request->merchantId;
        $createMerchantQrCode->object_type = 'express_merchant';
        $createMerchantQrCode->secret = $secretCode;
        $createMerchantQrCode->qr_image = $imageName;
        $createMerchantQrCode->status = 'Active';
        $createMerchantQrCode->save();
        
        $secretCodeImage = generateQrcode($createMerchantQrCode->secret);
        Image::make($secretCodeImage)->save(getDirectory('merchant_qrcode') . $imageName); 

        return response()->json([
            'status' => true,
            'imgSource' => image($imageName, 'merchant_qrcode')
        ]);
        
    }
    

}
