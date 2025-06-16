<?php

namespace App\Http\Controllers\Auth;

use App\Services\Mail\UserVerificationMailService;
use App\Http\Controllers\Users\EmailController;
use DB, Validator, Auth, Exception;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Helpers\Common;
use Illuminate\Support\Str;
use App\Models\{RoleUser,
    CryptoProvider,
    VerifyUser,
    QrCode,
    User,
    Role
};
use App\Mail\EmailOtp;
use App\Http\Controllers\CheckMobiController;
use Illuminate\Support\Facades\Mail;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class RegisterController extends Controller
{
    protected $helper;
    protected $email;
    protected $user;
    protected $referralIdentifier;

    public function __construct()
    {
        $this->helper = new Common();
        $this->email  = new EmailController();
        $this->user   = new User();
        if (module('Referral') && settings('referral_enabled') == 'Yes') {
            $this->referralIdentifier = md5(getBrowser($_SERVER['HTTP_USER_AGENT'])['platform'] . $_SERVER['REMOTE_ADDR']);
        }
        $this->key = 'WbUVSk7i3ZLhF1fYjqPPKQZGKdACOsmXQ87Xk06pMj9ZPpZ6WVHtSRbTHeziuyMp';
    }

    public function create()
    {
        $data = [
            'title' => 'Register'
        ];

        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('frontend.auth.register', $data);
    }

    public function storePersonalInfo(Request $request)
    {
        $this->validate($request, [
            'first_name'            => 'required|max:30|regex:/^[a-zA-Z\s]*$/',
            'last_name'             => 'required|max:30|regex:/^[a-zA-Z\s]*$/',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);

        $data = [
            'formInfo' => $request->all(),
            'enabledCaptcha' => settings('has_captcha'),
            'checkMerchantRole' => Role::where([
                'user_type' => 'User', 
                'customer_type' => 'merchant', 
                'is_default' => 'Yes'
            ])->first(['id']),
            'checkUserRole' => Role::where([
                'user_type' => 'User', 
                'customer_type' => 'user', 
                'is_default' => 'Yes'
            ])->first(['id'])
        ];
        captchaCheck(settings('has_captcha'), 'site_key');

        return view('frontend.auth.userType', $data);

    }
    
    // custom mobicheck
    public function verifyPhoneNumber(Request $request, $country = null, $code = null, $number = null) {
        $data = [];
        if(\Session::get('data'))
            $data = \Session::get('data');
        $country = $country ?? null;
        $code = $code ?? null;
        $number = $number ?? null;
        return view('frontend.auth.verifyPhone', $data, compact('code', 'number', 'country'));
    }
    
    public function verifyEmail(Request $request) {
        // $data['phone'] = isset($request->phone) ? $request->phone : null;
        // $data['full_name'] = isset($request->full_name) ? $request->full_name : '';
        try{
            $token = $request->token;
            $oritoken = deEncryptToken($token);
            $tokenPayload = JWT::decode($oritoken, new Key($this->key, 'HS256'));
            
            
            
            $data['full_name'] = '';
            $data['phone'] =  $tokenPayload->phone;
            if(!empty($tokenPayload->full_name))
                $data['full_name'] =  $tokenPayload->full_name;
            else {
                $data['full_name'] = $tokenPayload->first_name ?? '' . " " . $tokenPayload->last_name ?? '';
            }
                

            return view('frontend.auth.verifyEmail', $data);
        }
        catch (\Exception $error) {
            abort(404);
        }
       
    }
    public function checkPhoneExist(Request $request)
    {
        if (isset($request->carrierCode))
        {
            $user = User::where(['phone' => preg_replace("/[\s-]+/", "", $request->phone), 'carrierCode' => $request->carrierCode])->first(['phone', 'carrierCode']);
        }
        else
        {
            $user = User::where(['phone' => preg_replace("/[\s-]+/", "", $request->phone)])->first(['phone', 'carrierCode']);
        }

        if (!empty($user->phone) && !empty($user->carrierCode))
        {
            $data['status'] = false;
            $data['success']   = trans("The phone number is Available!");
        }
        else
        {
            $data['status']  = true;
            $data['fail'] = trans("User not found");
        }
        return json_encode($data);
    }
    
    // Send active to phone
    public function sendCodeToPhone(Request $request) {
        $user  = User::where('phone', $request->phone)->whereNotNull('phone')->first();
        try {
                
            if($user) {
                // update
                $check_mobil = new CheckMobiController();
                
                if($request->type == 'send-otp-reset-to-phone') {
                    
                    $is_send_phone_token = $check_mobil->sendResetPasswordToPhone($request->phone, $user);
                }
                else {
                  
                    if($user->mobile_check_status == 1) {
                        return response()->json([
                            'status' => true,
                            'message' => __('Your phone was verified'),
                        ]);
                    }
                    $is_send_phone_token = $check_mobil->sendCodeToPhone(($request->carrierCode . $request->phone), $user);
                    
                    
                }
                    
                if($is_send_phone_token == 1)
                    return response()->json([
                        'status' => false,
                        'message' => __("The otp was sent"),
                        
                    ]);
                else
                    return response()->json([
                        'status' => true,
                        'message' => __("Error sending otp"),
                        
                    ]);
            }
           else
                return response()->json([
                    'status' => true,
                    'message' => __("User not found"),
                    
                ]);

        } catch (\Exception $error) {

           // dd($error->getMessage());
            return response()->json([
                'code' => true,
                'message' => $error->getMessage(),
            ]);
        }
    }
    
    
    
    
    // send email otp
    public function sendEmailOtpVerify(Request $request)
    { 
        try {
            
            $user  = User::where(['email' => $request->email, 'phone' => $request->phone])->whereNotNull('email')->first();

            $findEmail = User::where(['email' => $request->email])->whereNotNull('email')->first();
            $findPhone = User::where(['phone' => $request->phone])->whereNotNull('phone')->first();
                
            if($user) {

                if($user->verified_email == 1) {
                    return response()->json([
                        'code' => 410,
                        'message' => trans('Your email has been verified, please try another email.'),
                    ]);
                }
                User::where('id', $user->id)->update([
                    'email_code' => rand(1000,99999)
                ]);

                if(sendEmail('email-otp', $request->email)) {
                    return response()->json([
                        'code' => 200,
                        'message' => trans("Email sent"),
                        
                    ]);
                }
                else {
                    return response()->json([
                        'code' => 409,
                        'message' => trans('Email not sent'),
                    ]);
                }
                    
            }
            else {
             
                // update email for phone
                $user  = User::where('phone', $request->phone)->first();
                if($user) {
                    
                    if($findEmail) {
                        return response()->json([
                            'code' => 409,
                            'message' => trans("Email exist"),
                            
                        ]);
                    }
                    else {
                        // update email and sent email
                        User::where('id', $user->id)->update([
                            'email' => $request->email,
                            'email_code' => rand(1000,99999)
                        ]);
                        
                        // send email
                        if(sendEmail('email-otp', $request->email)) {
                            return response()->json([
                                'code' => 200,
                                'message' => trans("Email sent"),
                                
                            ]);
                        }
                        else {
                            return response()->json([
                                'code' => 409,
                                'message' => trans('Email not sent'),
                            ]);
                        }
                    }
                
                    
                }
                else {
                    
                    // create new user for phone and email
                    if($findEmail) {
                        return response()->json([
                            'code' => 409,
                            'message' => trans("Email exist"),
                            
                        ]);
                    }
                    if($findPhone) {
                        return response()->json([
                            'code' => 409,
                            'message' => trans("Phone exist"),
                            
                        ]);
                    }
                    else {
                        // create new user for phone and email
                        $getName  = [];
                        if(!empty($request->full_name))
                            $getName = splitName($request->full_name);
                        $user = User::create([
                            'first_name' => array_key_exists(0, $getName) ? $getName[0] : '',
                            'last_name' => array_key_exists(1, $getName) ? $getName[1] : '',
                            'email' => $request->email,
                            'phone' => $request->phone,
                            'role_id' => 2,
                            'email_code' => rand(1000,99999),
                           
                        ]);
                        
                        
                        
                        //send email
                        if(sendEmail('email-otp', $request->email)) {
                            return response()->json([
                                'code' => 200,
                                'message' => trans("Email sent"),
                                
                            ]);
                        }
                        else {
                            return response()->json([
                                'code' => 409,
                                'message' => trans('Email not sent'),
                            ]);
                        }
                    }
                
                    
                }
                
            }
            
            
            
            
        } catch (\Exception $error) {
            return response()->json([
                'code' => 409,
                'message' => $error->getMessage(),
            ]);
        }
    }
    
    // active email by code
    public function activeEmail(Request $request) {

        $user  = User::where(['email_code' => $request->email_code, 'phone' => $request->phone])->whereNotNull('email_code')->first();
        try {
                
            if($user) {
                // update
                User::where('email_code', $request->email_code)->update([
                    'email_code' => NULL,
                    'verified_email' => 1,
                    'mobile_check_status' => 1,
                ]);

                // update user
                RoleUser::insert(['user_id' => $user->id, 'role_id' => $user->role_id, 'user_type' => 'User']);

                // Create user detail
                $user->createUserDetail($user->id);
                
                // set login 
                auth()->login($user, true);
                
                return response()->json([
                    'code' => 200,
                    'message' => trans("Email verified"),
                    
                ]);
            }
           else
               return response()->json([
                'code' => 400,
                'message' => trans("Otp not found"),
            
        ]);

        } catch (\Exception $error) {
            return response()->json([
                'code' => 409,
                'message' => trans('Error update data'),
            ]);
        }
    }
    
    
    // Active account
	public function activeAccount(Request $request) {
        
        //$request->phone = preg_replace('/\s+/', '', $request->phone);
        
        $user  = User::where('phone', $request->phone)->whereNotNull('phone')->first();
        $mobile = new CheckMobiController();
        if($user) {
            if(!$user->mobile_check_id && $user->mobile_check_status == 1) {
                return response()->json([
                    'status' => true,
                    'message' => trans('Your phone was verified'),
                ]);
            }
            else {
                $request->merge(['id' => $user->mobile_check_id, 'pin' => $request->otp_code]);
                $token = $mobile->returnVerifyPin($request);
               
	            try {
	                    
	                if($token) {

                      
	                    // update
	                    User::where(['id' => $user->id])->update([
	                        'mobile_check_status' => 1,
	                        'mobile_check_id' => null,
	                    ]);
	                    return response()->json([
	                        'status' => false,
	                        'message' => trans("Phone verified"),
	                        
	                    ]);
	                }
	               else if($token == 0)
	                    return response()->json([
	                        'status' => true,
	                        'message' => trans("Otp not found"),
	                        
	                    ]);
	                else
                        return response()->json([
                            'status' => true,
                            'message' => trans("Many request, please try later"),
                            
                        ]);
	    
	            } catch (\Exception $error) {
	                return response()->json([
	                    'status' => true,
	                    'message' => trans('Otp not found'),
	                ]);
	            }
            }
            
        }
        else {
            return response()->json([
                'status' => true,
                'message' => trans('User not found'),
            ]);
        }
            
        
    }
    // end custom

    public function store(Request $request)
    {
        $data = $request->all();
        captchaCheck(settings('has_captcha'), 'secret_key');

        if ($request->isMethod('post')) {
            if ($request->has_captcha == 'registration' || $request->has_captcha == 'login_and_registration') {
                $rules = array(
                    'first_name'            => ['required', 'max:30', 'regex:/^[a-zA-Z\s]*$/'],
                    'last_name'             => ['required', 'max:30', 'regex:/^[a-zA-Z\s]*$/'],
                    'email'                 => ['required', 'email', 'unique:users,email'],
                    //'phone'                 => 'nullable|unique:users',
                    'password'              => ['required', 'confirmed'],
                    'password_confirmation' => ['required'],
                    'g-recaptcha-response'  => ['required', 'captcha'],
                );
                
                $fieldNames = array(
                    'first_name'            => 'First Name',
                    'last_name'             => 'Last Name',
                    'email'                 => 'Email',
                    'password'              => 'Password',
                    'password_confirmation' => 'Confirm Password',
                    'g-recaptcha-response'  => 'Captcha'
                );

            } else {
                $rules = array(
                    'first_name'            => ['required', 'max:30', 'regex:/^[a-zA-Z\s]*$/'],
                    'last_name'             => ['required', 'max:30', 'regex:/^[a-zA-Z\s]*$/'],
                    'email'                 => ['required', 'email', 'unique:users,email'],
                    //'phone'                 => 'nullable|unique:users',
                    'password'              => ['required', 'confirmed'],
                    'password_confirmation' => ['required'],
                );
                $fieldNames = array(
                    'first_name'            => 'First Name',
                    'last_name'             => 'Last Name',
                    //'phone'                 => 'Phone',
                    'email'                 => 'Email',
                    'password'              => 'Password',
                    'password_confirmation' => 'Confirm Password',
                );
            }

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);
            if ($validator->fails()) {
                return redirect('register')->withErrors($validator)->withInput();
            } else {
                try {
                    
                    // if (User::where('phone', preg_replace("/[\s-]+/", "", $request->phone))->exists()) {
                    //     $this->helper->one_time_message('error', __('The phone number has already been taken!'));
                    //     return redirect('register')->withErrors($validator)->withInput();
                    // }

                    DB::beginTransaction();

                    // Create user
                    $user = $this->user->createNewUser($request, 'user');

                    // Assign user type and role to new user
                    RoleUser::insert(['user_id' => $user->id, 'role_id' => $user->role_id, 'user_type' => 'User']);

                    // Create user detail
                    $this->user->createUserDetail($user->id);

                    // Create user's default wallet
                    $this->user->createUserDefaultWallet($user->id, settings('default_currency'));

                    // Create wallets that are allowed by admin
                    if (settings('allowed_wallets') != 'none') {
                        $this->user->createUserAllowedWallets($user->id, settings('allowed_wallets'));
                    }

                    if (module('Referral') && settings('referral_enabled') == 'Yes') {
                        // Save referral code for new User
                        (new \Modules\Referral\Entities\ReferralCode())->saveUserReferralCode($user->id);

                        // Check Cache & Save to Referrals - starts
                        (new \Modules\Referral\Entities\Referral())->saveReferralWithCacheCheck($user->id);

                        // signup referral award
                        (new \Modules\Referral\Entities\ReferralAward())->saveSignupReferralAward($user->id);
                    }

                  
                    if (isActive('TatumIo') && CryptoProvider::getStatus('TatumIo') == 'Active') {
                        $generateUserCryptoWalletAddress = $this->user->generateUserTatumIoWalletAddress($user);
                        if ($generateUserCryptoWalletAddress['status'] == 401) {
                            DB::rollBack();
                            $this->helper->one_time_message('error', $generateUserCryptoWalletAddress['message']);
                            //return redirect('/login');
                            //return redirect('/register/verify-phone')->with('data', $data);
                            return redirect()->route('register.verifyPhone.info', [$request->defaultCountry, $request->carrierCode, $request->formattedPhone])->with('data', $data);
                           // return view('frontend.auth.verifyPhone', $data);
                        }
                    }
                    
                    // QR Code
                    QrCode::createUserQrCode($user);

                    $userEmail          = $user->email;
                    $userFormattedPhone = $user->formattedPhone;

                    // Process Registered User Transfers
                    $this->user->processUnregisteredUserTransfers($userEmail, $userFormattedPhone, $user, settings('default_currency'));

                    // Process Registered User Request Payments
                    $this->user->processUnregisteredUserRequestPayments($userEmail, $userFormattedPhone, $user, settings('default_currency'));

                    // Email verification
                    if (!$user->user_detail->email_verification) {
                        if (preference('verification_mail') == "Enabled") {
                            VerifyUser::generateVerificationToken($user->id);
                            try {
                                (new UserVerificationMailService)->send($user);

                                DB::commit();
                                $this->helper->one_time_message('success', __('We sent you an activation code. Check your email and click on the link to verify.'));
                                //return redirect('/login');
                                return redirect()->route('register.verifyPhone.info', [$request->defaultCountry, $request->carrierCode, $request->formattedPhone])->with('data', $data);
                            } catch (Exception $e) {
                                DB::rollBack();
                                $this->helper->one_time_message('error', $e->getMessage());
                                //return redirect('/login');
                                //return redirect('/register/verify-phone')->with('data', $data);
                                return redirect()->route('register.verifyPhone.info', [$request->defaultCountry, $request->carrierCode, $request->formattedPhone])->with('data', $data);
                               // return view('frontend.auth.verifyPhone', $data);
                            }
                        }
                    }
                    //email_verification - ends
                    DB::commit();
                    $this->helper->one_time_message('success', __('Registration Successful!'));
                    //return redirect('/login');
                    return redirect()->route('register.verifyPhone.info', [$request->defaultCountry, $request->carrierCode, $request->formattedPhone])->with('data', $data);
                } catch (Exception $e) {
                    DB::rollBack();
                    $this->helper->one_time_message('error', $e->getMessage());
                    //return redirect('/login');
                    return redirect()->route('register.verifyPhone.info', [$request->defaultCountry, $request->carrierCode, $request->formattedPhone])->with('data', $data);
                    
                }
            }
        }
    }

    public function verifyUser($token)
    {
        $verifyUser = VerifyUser::where('token', $token)->first();
        if (isset($verifyUser))
        {
            if (!$verifyUser->user->user_detail->email_verification)
            {
                $verifyUser->user->user_detail->email_verification = 1;
                $verifyUser->user->user_detail->save();
                $status = __("Your account is verified. You can now login.");
            }
            else
            {
                $status = __("Your account is already verified. You can now login.");
            }
        }
        else
        {
            return redirect('/login')->with('warning', __("Sorry your email cannot be identified."));
        }
        return redirect('/login')->with('status', $status);
    }

    public function checkUserRegistrationEmail(Request $request)
    {
        $email = User::where(['email' => $request->email])->exists();
        if ($email)
        {
            $data['status'] = true;
            $data['fail']   = __('The email has already been taken!');
        }
        else
        {
            $data['status']  = false;
            $data['success'] = trans("Email Available!");
        }
        return json_encode($data);
    }

    public function registerDuplicatePhoneNumberCheck(Request $request)
    {
        if (isset($request->carrierCode))
        {
            $user = User::where(['phone' => preg_replace("/[\s-]+/", "", $request->phone), 'carrierCode' => $request->carrierCode])->first(['phone', 'carrierCode']);
        }
        else
        {
            $user = User::where(['phone' => preg_replace("/[\s-]+/", "", $request->phone)])->first(['phone', 'carrierCode']);
        }

        if (!empty($user->phone) && !empty($user->carrierCode))
        {
            $data['status'] = true;
            $data['fail']   = trans("The phone number has already been taken!");
        }
        else
        {
            $data['status']  = false;
            $data['success'] = trans("The phone number is Available!");
        }
        return json_encode($data);
    }
}
