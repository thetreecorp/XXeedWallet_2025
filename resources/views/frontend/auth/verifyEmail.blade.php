@extends('frontend.layouts.app')
@section('styles')
    <style>
        .success-mess {
            color: #fff;
        }
        .alert {
            padding: 20px;
            background-color: #f44336;
            color: white;
            margin: 7px 0;
            display: block;
        }
        .alert.error {

            color: white !important;
        }
        .alert.success {
            background-color: #04AA6D;
        }
        
        .closebtn {
          margin-left: 15px;
          color: white;
          font-weight: bold;
          float: right;
          font-size: 22px;
          line-height: 20px;
          cursor: pointer;
          transition: 0.3s;
        }
        
        .closebtn:hover {
          color: black;
        }
        .auth-module {
            margin-top: 150px
        }
    </style>
@endsection

@section('content')
    <!-- Login page start -->
    <div class="container-fluid container-layout px-0" id="register-form">
        <div class="main-auth-div">
            <div class="row">
                <div class="col-md-6 col-xl-5 hide-small-device">
                    <div class="bg-pattern">
                        <div class="bg-content">
                            <div class="d-flex justify-content-start"> 
                                <div class="logo-div">
                                    <a href="{{ url('/') }}">
                                        <img src="{{ image(settings('logo'), 'logo') }}" alt="{{ __('Brand Logo') }}">
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
                                    <img class="img img-fluid" src="{{ asset('public/frontend/templates/images/login/signup-static-img.svg') }}">
                                </div>
                            </div>
                        </div>
                    </div>                  
                </div>
                <div class="col-md-6 col-12 col-xl-7">
                    
                    <div class="auth-section d-flex align-items-center">
                        
                          <div class="auth-module">
                            <form action="/" class="form-horizontal" id="verify-email-form" method="POST">
                                @csrf
                                
                                <input type="hidden" name="has_captcha" value="{{ isset($enabledCaptcha) && ($enabledCaptcha == 'registration' || $enabledCaptcha == 'login_and_registration') ? 'registration' : 'Disabled' }}">
                               
                               
                                <input type="hidden" name="full_name" id="full_name" class="form-control" value="{{ $full_name ?? '' }}">
                                <input type="hidden" name="phone" id="u-phone" class="form-control" value="{{ $phone ?? '' }}">

                                <div class="row">
                                    <div class="col-md-12">
                                    
                                        <div class="form-group mb-3">
                                            <label class="form-label">{{ __('Update email and get otp') }}</label>
                                            <input type="email" class="form-control input-form-control not-focus-bg" id="verify-email" value="" name="email">
                                            <div id="send-mb-notice"></div>
                                        </div>
                                        
                                        <div class="button-wrap">
                                            <a id="send-code-email" class="d-block btn btn-lg btn-primary" href="javascript:void(0)">{{ __('Get code') }}</a>
                                        </div>

                                        <div class="form-group mb-3 mt-3 ">
                                            <div>
                                                <label class="form-label">{{ __('Email Code') }}</label>
                                                <input  placeholder="{{ __('Email Code') }}" type="number" class="form-control input-form-control not-focus-bg" id="verify-code" name="verify-code">
                                                
                                                <div id="message-verify"></div>
                                            </div>
                                            
                                            
                                        </div>
                                        
                                        
                                        <!-- reCaptcha -->
                                        @if (isset($enabledCaptcha) && ($enabledCaptcha == 'registration' || $enabledCaptcha == 'login_and_registration'))

                                            <div class="col-md-12 mt-3">
                                                {!! app('captcha')->display() !!}
                                                @error ('g-recaptcha-response')
                                                    <span class="error">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    <br>
                                                @enderror
                                                <br>
                                            </div>
                                        @endif
                                       
                                        <div class="d-grid sm-top mt-3">
                                            <button class="btn btn-lg btn-primary" type="submit" id="verifyEmailBtn">
                                                <div class="spinner spinner-border text-white spinner-border-sm mx-2 d-none" role="status">
                                                    <span class="visually-hidden"></span>
                                                </div>
                                                <span class="px-1" id="verifyEmailBtnTxt">{{ __('Verify Email') }}</span>
                                                <span id="rightAngle">{!! svgIcons('right_angle_md') !!}</span>
                                            </button>
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
    <!-- Login page end -->
@endsection

@section('js')
<script src="{{ asset('public/dist/plugins/html5-validation-1.0.0/validation.min.js') }}" type="text/javascript"></script>

<script>
    'use strict';
    let requiredText = '{{ __("This field is required.") }}';
    let validEmailText = '{{ __("Please enter a valid email address.") }}';
    let samePasswordText = '{{ __("Please enter the same value again.") }}'
    let confirmSamePasswordText = '{{ __("Please enter same value as the password field.") }}';
    let alphabetSpaceText = '{{ __("Please enter only alphabet and spaces.") }}';
    let signingUpText = '{{ __("Continuing...") }}';
    let countryShortCode = '{{ getDefaultCountry() }}';

    let waitMessage = '{{ __("Please wait before submitting the form again.") }}';
</script>
<script src="{{ asset('public/frontend/customs/js/register/register.js') }}" type="text/javascript"></script>

@endsection
