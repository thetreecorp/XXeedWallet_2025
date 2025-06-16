<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Common;
use App\Models\CryptoProvider;
use App\Models\QrCode;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\VerifyUser;
use App\Services\Mail\UserVerificationMailService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;


class SocialiteController extends Controller
{
    protected $user;
    protected $helper;

    public function __construct()
    {
        $this->user = new User();
        $this->helper = new Common();
    }

    public function redirectToProvider($provider)
    {
        $provider = $provider == 'linkedin' ? 'linkedin-openid' : $provider;
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $provider = $provider == 'linkedin' ? 'linkedin-openid' : $provider;
        try {
            $user = Socialite::driver($provider)->user();
            return $this->complete_social_login($user);
            //echo $user->id;
            //echo $user->user['given_name'] . ' ' . $user->user['family_name'];
            /**
             * If create then insert into:
             * 1. user table
             * 2. user role table --> fetch role by id 2
             * 3. user_details table
             */
        } catch (\Exception $e) {
            $this->helper->one_time_message('error', $e->getMessage());
            return redirect('/login');
        }
    }

    protected function complete_social_login($socialuser) {
        try {
            $user = User::where('email', $socialuser->email)->first();

            if (!$user) {
                DB::beginTransaction();
                $user = $this->user->createNewSocialUser($socialuser);

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

                // if (module('Referral') && settings('referral_enabled') == 'Yes') {
                //     // Save referral code for new User
                //     (new \Modules\Referral\Entities\ReferralCode())->saveUserReferralCode($user->id);

                //     // Check Cache & Save to Referrals - starts
                //     (new \Modules\Referral\Entities\Referral())->saveReferralWithCacheCheck($user->id);

                //     // signup referral award
                //     (new \Modules\Referral\Entities\ReferralAward())->saveSignupReferralAward($user->id);
                // }

            
                if (isActive('TatumIo') && CryptoProvider::getStatus('TatumIo') == 'Active') {
                    $generateUserCryptoWalletAddress = $this->user->generateUserTatumIoWalletAddress($user);
                    if ($generateUserCryptoWalletAddress['status'] == 401) {
                        DB::rollBack();
                        $this->helper->one_time_message('error', $generateUserCryptoWalletAddress['message']);
                        //return redirect('/login');
                        //return redirect('/register/verify-phone')->with('data', $data);
                        return redirect('/login');
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
                // if (!$user->user_detail->email_verification) {
                //     if (preference('verification_mail') == "Enabled") {
                //         VerifyUser::generateVerificationToken($user->id);
                //         try {
                //             (new UserVerificationMailService)->send($user);

                //             DB::commit();
                //             $this->helper->one_time_message('success', __('We sent you an activation code. Check your email and click on the link to verify.'));
                //             //return redirect('/login');
                //             return redirect()->route('register.verifyPhone.info', [$request->defaultCountry, $request->carrierCode, $request->formattedPhone])->with('data', $data);
                //         } catch (Exception $e) {
                //             DB::rollBack();
                //             $this->helper->one_time_message('error', $e->getMessage());
                //             redirect('/login');
                //         }
                //     }
                // }
                //email_verification - ends
                DB::commit();
            }

            Auth::login($user);
            return redirect()->route('user.dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->helper->one_time_message('error', $e->getMessage());
            return redirect('/login');
        }
    }
}
