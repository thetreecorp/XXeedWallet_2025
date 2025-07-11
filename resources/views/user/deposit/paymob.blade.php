
@extends('user.layouts.app')

@section('content')
<div class="bg-white pxy-62 shadow" id="depositPaymob">
    <p class="mb-0 f-26 gilroy-Semibold text-uppercase text-center">{{ __('Deposit Money') }}</p>
    <p class="mb-0 text-center f-13 gilroy-medium text-gray mt-4 dark-A0">{{ __('Step: 2 of 3') }}</p>
    <p class="mb-0 text-center f-18 gilroy-medium text-dark dark-5B mt-2">{{ __('Confirm your Deposit') }}</p>
    <div class="text-center">{!! svgIcons('stepper_confirm') !!}</div>
    <p class="mb-0 text-center f-14 gilroy-medium text-gray dark-p mt-20">{{ __('Check your deposit information before confirmation.') }}</p>
    @include('user.common.alert')
    <div class="mt-32 param-ref text-center">
        <div id="paymob-button-container">

        </div>
    </div>
</div>
@endsection
@push('js')
{{--<script src="https://www.paypal.com/sdk/js?client-id={{ $authorizationKey }}&disable-funding=paylater&currency={{ $currencyCode }}"></script>--}}
<script>
    'use strict';
    var token = $('[name="_token"]').val();
    var types = JSON.parse('{!! json_encode($types) !!}');
    var redirect_url = "{!! route('paymob.payment') !!}"+"?currency_id="+"{!! $currency_id !!}"+"&method_id="+"{!! $method_id !!}"+"&paymob_payment_id="+"{!! $paymob_payment_id !!}"+"&paymob_payment_type=";
</script>
<script src="{{ asset('public/user/customs/js/deposit.js') }}"></script>
@endpush
