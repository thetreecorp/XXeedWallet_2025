@extends('frontend.layouts.app')
@section('styles')
    <style>
        .flex-space{
            display: flex;
        	flex-direction: row;
        	flex-wrap: nowrap;
        	justify-content: space-between;
        	align-items: stretch;
        	align-content: stretch;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('frontend/templates/css/toastr.min.css') }}">
@endsection
@section('content')
    <!-- Login section start -->
    <div class="container-fluid container-layout px-0">
        <div class="main-auth-div">
            <div class="row">
                <div class="col-md-6 col-xl-5 hide-small-device">
                    <div class="bg-pattern">
                        <div class="bg-content">
                            <div class="d-flex justify-content-start">
                                <div class="logo-div">
                                    <a href="{{ url('/') }}">
                                        {{-- <img src="{{ image(settings('logo'), 'logo') }}" alt="{{ __('Brand Logo') }}"> --}}
                                        <img src="{{ asset('/uploads/logos/1734986270_logo.png') }}" alt="{{ __('Brand Logo') }}">

                                    </a>
                                </div>
                            </div>
                            <div class="transaction-block">
                                <div class="transaction-text">
                                    <h3 class="mb-6p">{{ __('Hassle free money') }}</h3>
                                    <h1 class="mb-2p">{{ __('Transactions') }}</h1>
                                    <h2>{{ __('Right at you fingertips') }}</h2>
                                </div>
                            </div>
                            <div class="transaction-image">
                                <div class="static-image">
                                    <img class="img img-fluid" src="{{ asset('frontend/templates/images/login/signin-static.svg') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12 col-xl-7">
                    <div class="auth-section signin-top d-flex align-items-center">
                          <div class="auth-module">
                            <div class="d-flex align-items-center back-direction mb-2 back-signin">
                                <a href="{{ url('/') }}" class="d-inline-flex align-items-center back-btn">
                                  {!! svgIcons('left_angle') !!}
                                  <span class="ms-1">{{ __('Back') }}</span>
                                </a>
                            </div>
                            <form action="{{ url('authenticate') }}" method="post" id="login-form">
                                @csrf
                                <input type="hidden" name="has_captcha" value="{{ isset($setting['has_captcha']) && ($setting['has_captcha'] == 'login' || $setting['has_captcha'] == 'login_and_registration') ? 'login' : 'Disabled' }}">

                                <input type="hidden" name="login_via" value="
                                @if (isset($setting['login_via']) && ($setting['login_via'] == 'email_only'))
                                    {{ "email_only" }}
                                @elseif(isset($setting['login_via']) && ($setting['login_via'] == 'phone_only'))
                                    {{ "phone_only" }}
                                @else
                                    {{ "email_or_phone" }}
                                @endif
                                ">

                                <input type="hidden" name="browser_fingerprint" id="browser_fingerprint" value="test">

                                <div class="d-flex align-items-center back-direction auth-back d-none">
                                    <a href="{{ url('/') }}" class="d-inline-flex align-items-center back-btn">
                                      <svg class="position-relative" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8.47075 10.4709C8.7311 10.2105 8.7311 9.78842 8.47075 9.52807L4.94216 5.99947L8.47075 2.47087C8.7311 2.21053 8.7311 1.78842 8.47075 1.52807C8.2104 1.26772 7.78829 1.26772 7.52794 1.52807L3.52795 5.52807C3.2676 5.78842 3.2676 6.21053 3.52795 6.47088L7.52794 10.4709C7.78829 10.7312 8.2104 10.7312 8.47075 10.4709Z" fill="currentColor"></path>
                                      </svg>
                                      <span class="ms-1">{{ __('Back') }}</span>
                                    </a>
                                </div>

                                <div class="auth-module-header">
                                    <p class="mb-0 text-start auth-title">{{ __('Sign In') }}</p>
                                    <p class="mb-0 text-start auth-text mt-12"> {{ __('Don’t have an account?') }}<a href="{{ url('/register') }}"> {{ __('Sign up here') }}</a></p>
                                    <p class="mb-0 text-start  auth-text mt-12">
                                        <a href="{{ createSsoUrl('login') }}">
                                            <img style="max-width: 30px;" class="img img-fluid" src="{{ asset('uploads/logos/kemedar.svg') }}">
                                        </a>
                                        <a href="{{ createrRibanoUrl('login') }}">
                                            <img style="max-width: 30px;" class="img img-fluid" src="{{ asset('uploads/logos/ribano.svg') }}">
                                        </a>

                                        <!-- Added by Sh!Mul -->
                                        <a href="{{ route('provider.auth', ['provider' => 'google']) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 16 16"
                                                style="fill: red; margin-left: 5px; margin-top: 3px">
                                                <path d="M15.545 6.558a9.4 9.4 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.7 7.7 0 0 1 5.352 2.082l-2.284 2.284A4.35 4.35 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.8 4.8 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.7 3.7 0 0 0 1.599-2.431H8v-3.08z"/>
                                            </svg>
                                        </a>

                                        <a href="{{ route('provider.auth', ['provider' => 'linkedin']) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16"
                                                style="fill: #0A66C2; margin-left: 5px; margin-top: 3px">
                                                <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225z"/>
                                            </svg>
                                        </a>
                                    </p>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        @if (isset($setting['login_via']) && $setting['login_via'] == 'email_only')
                                            <div class="form-group mb-3">
                                                <label class="form-label">{{ __('Email Address') }} <span class="star">*</span></label>
                                                <input type="email" class="form-control input-form-control" placeholder="{{ __('Email Address') }}" name="email_only" id="email_only" required="" data-value-missing="{{ __('This field is required.') }}">
                                                @error('email_only')
                                                    <span class="error"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        @elseif(isset($setting['login_via']) && $setting['login_via'] == 'phone_only')
                                            <div class="form-group mb-3">
                                                <label for="phone_only" class="form-label">{{ __('Phone') }} <span class="star">*</span></label>
                                                <input type="text" class="form-control input-form-control" placeholder="{{ __('Phone') }}" name="phone_only" id="phone_only" required=""  data-value-missing="{{ __('This field is required.') }}">
                                                @error('phone_only')
                                                    <span class="error"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        @elseif(isset($setting['login_via']) && $setting['login_via'] == 'email_or_phone')
                                            <div class="form-group mb-3">
                                                <label for="email_or_phone" class="form-label">{{ __('Email or Phone') }} <span class="star">*</span></label>
                                                <input type="text" class="form-control input-form-control" placeholder="{{ __('Email or Phone') }}" name="email_or_phone" id="email_or_phone" required="" data-value-missing="{{ __('This field is required.') }}">
                                                @error('email_or_phone')
                                                    <span class="error">
                                                     {{ $message}}
                                                    </span>
                                                @enderror
                                            </div>
                                        @endif
                                        <div class="form-group mb-3">
                                            <label class="form-label">{{ __('Password') }} <span class="star">*</span></label>
                                            <div id="show_hide_password" class="position-relative">
                                                <input type="password" class="form-control input-form-control" id="password" placeholder="{{ __('Password') }}" name="password" required=""  data-value-missing="{{ __('This field is required.') }}">
                                                <span class="eye-icon cursor-pointer" id="eye-icon-show">
                                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M1.71998 1.71967C2.01287 1.42678 2.48775 1.42678 2.78064 1.71967L5.50969 4.44872C5.55341 4.48345 5.59378 4.52354 5.62977 4.5688L13.423 12.3621C13.4739 12.4011 13.5204 12.4471 13.561 12.5L16.2806 15.2197C16.5735 15.5126 16.5735 15.9874 16.2806 16.2803C15.9877 16.5732 15.5129 16.5732 15.22 16.2803L12.8547 13.915C11.771 14.5491 10.479 15 9.00031 15C6.85406 15 5.10432 14.0515 3.80787 12.9694C2.51318 11.8889 1.62553 10.6393 1.18098 9.93536C1.1751 9.92606 1.16907 9.91657 1.16291 9.90687C1.07468 9.768 0.960135 9.5877 0.902237 9.33506C0.85549 9.13108 0.855506 8.86871 0.902276 8.66474C0.960212 8.41207 1.07508 8.23131 1.16354 8.09212C1.16975 8.08235 1.17583 8.07278 1.18175 8.06341C1.63353 7.34824 2.55099 6.05644 3.89682 4.95717L1.71998 2.78033C1.42709 2.48744 1.42709 2.01256 1.71998 1.71967ZM4.96371 6.02406C3.73433 6.99464 2.87554 8.19074 2.44991 8.86452C2.42329 8.90666 2.40463 8.93624 2.38903 8.96192C2.37862 8.97905 2.37176 8.99088 2.36719 8.99912C2.36719 8.99941 2.36719 8.99969 2.36719 8.99998C2.36719 9.00029 2.36719 9.00059 2.36719 9.00089C2.3717 9.00902 2.37845 9.02067 2.38868 9.0375C2.40417 9.06302 2.42272 9.09243 2.44923 9.1344C2.84872 9.76697 3.6393 10.8749 4.76902 11.8178C5.89697 12.7592 7.31781 13.5 9.00031 13.5C10.015 13.5 10.9334 13.2311 11.7506 12.8109L10.5242 11.5845C10.0776 11.8483 9.55635 12 9.00031 12C7.34346 12 6.00031 10.6569 6.00031 9C6.00031 8.44396 6.15203 7.92272 6.41579 7.47614L4.96371 6.02406ZM7.551 8.61135C7.51791 8.73524 7.50031 8.86549 7.50031 9C7.50031 9.82843 8.17188 10.5 9.00031 10.5C9.13482 10.5 9.26507 10.4824 9.38896 10.4493L7.551 8.61135ZM9.00031 4.5C8.71392 4.5 8.43614 4.52137 8.1669 4.56117C7.75714 4.62176 7.37586 4.33869 7.31527 3.92893C7.25469 3.51917 7.53776 3.13789 7.94751 3.0773C8.28789 3.02698 8.63899 3 9.00031 3C11.1466 3 12.8963 3.94854 14.1928 5.03057C15.4874 6.11113 16.3751 7.36072 16.8196 8.06464C16.8255 8.07394 16.8316 8.08343 16.8377 8.09312C16.9259 8.23201 17.0405 8.41232 17.0984 8.66498C17.1451 8.86897 17.1451 9.13136 17.0983 9.33533C17.0404 9.58804 16.9253 9.76906 16.8367 9.90844C16.8305 9.91825 16.8244 9.92786 16.8184 9.93727C16.5797 10.3152 16.2174 10.8436 15.7374 11.4168C15.4715 11.7344 14.9985 11.7763 14.6809 11.5104C14.3633 11.2445 14.3214 10.7714 14.5873 10.4539C15.0158 9.94209 15.3393 9.47006 15.5503 9.13608C15.577 9.09384 15.5957 9.06416 15.6114 9.0384C15.6219 9.02109 15.6288 9.00916 15.6334 9.00086C15.6334 9.00059 15.6334 9.00031 15.6334 9.00003C15.6334 8.99972 15.6334 8.99942 15.6334 8.99911C15.6289 8.99099 15.6222 8.97934 15.6119 8.9625C15.5965 8.93698 15.5779 8.90757 15.5514 8.8656C15.1519 8.23303 14.3613 7.12506 13.2316 6.18218C12.1037 5.24078 10.6828 4.5 9.00031 4.5Z" fill="#6A6B87"></path>
                                                    </svg>
                                                </span>
                                                <span class="eye-icon-hide d-none cursor-pointer" id="eye-icon-hide">
                                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4.76901 6.18218C3.63929 7.12505 2.84871 8.23303 2.44922 8.8656C2.42271 8.90757 2.40417 8.93697 2.38868 8.96248C2.37845 8.97932 2.3717 8.99097 2.36719 8.99909C2.36719 8.99939 2.36719 8.9997 2.36719 9C2.36719 9.0003 2.36719 9.00061 2.36719 9.00091C2.3717 9.00903 2.37845 9.02068 2.38868 9.03752C2.40417 9.06303 2.42271 9.09243 2.44922 9.1344C2.84871 9.76697 3.63929 10.8749 4.76901 11.8178C5.89696 12.7592 7.3178 13.5 9.0003 13.5C10.6828 13.5 12.1036 12.7592 13.2316 11.8178C14.3613 10.8749 15.1519 9.76697 15.5514 9.1344C15.5779 9.09243 15.5964 9.06303 15.6119 9.03751C15.6222 9.02068 15.6289 9.00903 15.6334 9.00091C15.6334 9.00061 15.6334 9.0003 15.6334 9C15.6334 8.9997 15.6334 8.99939 15.6334 8.99909C15.6289 8.99097 15.6222 8.97932 15.6119 8.96249C15.5964 8.93697 15.5779 8.90757 15.5514 8.8656C15.1519 8.23303 14.3613 7.12505 13.2316 6.18218C12.1036 5.24077 10.6828 4.5 9.0003 4.5C7.3178 4.5 5.89696 5.24078 4.76901 6.18218ZM3.80786 5.03057C5.10431 3.94854 6.85405 3 9.0003 3C11.1466 3 12.8963 3.94854 14.1927 5.03057C15.4874 6.11113 16.3751 7.36071 16.8196 8.06464C16.8255 8.07394 16.8315 8.08343 16.8377 8.09313C16.9259 8.23198 17.0405 8.41227 17.0984 8.66488C17.1451 8.86884 17.1451 9.13116 17.0984 9.33512C17.0405 9.58773 16.9259 9.76802 16.8377 9.90687C16.8315 9.91657 16.8255 9.92606 16.8196 9.93536C16.3751 10.6393 15.4874 11.8889 14.1927 12.9694C12.8963 14.0515 11.1466 15 9.0003 15C6.85405 15 5.10431 14.0515 3.80786 12.9694C2.51318 11.8889 1.62553 10.6393 1.18097 9.93536C1.17509 9.92606 1.16906 9.91657 1.1629 9.90688C1.07469 9.76802 0.960152 9.58774 0.902251 9.33512C0.8555 9.13116 0.8555 8.86884 0.902251 8.66488C0.960152 8.41226 1.07469 8.23198 1.1629 8.09312C1.16906 8.08343 1.17509 8.07394 1.18097 8.06464C1.62553 7.36071 2.51318 6.11113 3.80786 5.03057ZM9.0003 7.5C8.17188 7.5 7.5003 8.17157 7.5003 9C7.5003 9.82843 8.17188 10.5 9.0003 10.5C9.82873 10.5 10.5003 9.82843 10.5003 9C10.5003 8.17157 9.82873 7.5 9.0003 7.5ZM6.0003 9C6.0003 7.34315 7.34345 6 9.0003 6C10.6572 6 12.0003 7.34315 12.0003 9C12.0003 10.6569 10.6572 12 9.0003 12C7.34345 12 6.0003 10.6569 6.0003 9Z" fill="#6A6B87"></path>
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                        <!-- reCaptcha -->
                                        @if (isset($setting['has_captcha']) && ($setting['has_captcha'] == 'login' || $setting['has_captcha'] == 'login_and_registration'))
                                            <div class="form-group mb-3">
                                                {!! app('captcha')->display() !!}

                                                @if ($errors->has('g-recaptcha-response'))
                                                    <span class="error">
                                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                                    </span>
                                                    <br>
                                                @endif
                                                <br>
                                            </div>
                                        @endif
                                        <div class="d-grid mb-3 mb-3p">
                                            <button class="btn btn-lg btn-primary" type="submit" id="login-btn">
                                                <div class="spinner spinner-border text-white spinner-border-sm mx-2 d-none" role="status">
                                                    <span class="visually-hidden"></span>
                                                </div>
                                                <span class="px-1" id="login-btn-text">{{ __('Login') }}</span>
                                                <span id="rightAngleSvgIcon">{!! svgIcons('right_angle_md') !!}</span>

                                            </button>
                                        </div>
                                        <div class="forgot-link flex-space mb-3 pt-1">
                                            {{-- <a href="{{ createSsoUrl('login') }}">{{ __('Login with SSO') }}</a>
                                            <a href="{{ createrRibanoUrl('login') }}">{{ __('Login with RIBANO') }}</a> --}}
                                        </div>
                                        <div class="forgot-link flex-space mb-3 pt-1">
                                            <a href="{{ url('forget-password') }}">{{ __('Forgot Password?') }}</a>
                                        </div>
                                    </div>
                               </div>
                            </form>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Login section end -->
@endsection
@section('js')

<script src="{{ asset('/dist/libraries/fingerprintjs2/fingerprintjs2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/dist/plugins/html5-validation-1.0.0/validation.min.js') }}"></script>
<script src="{{ asset('/frontend/templates/js/toastr.min.js') }}" type="text/javascript"></script>


<script>
    'use strict';
    let errorMessage = "{{ session()->has('status') ? session('status') : session('message')  }}";
    let errorMessageClass = "{{ session('alert-class') }}";
    let loginButtonText= "{{ __('Signing In...') }}";
</script>
<script src="{{ asset('/frontend/customs/js/login/login.min.js') }}" type="text/javascript"></script>

@endsection

