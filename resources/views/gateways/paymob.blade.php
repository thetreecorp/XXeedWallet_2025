@extends('gateways.layouts.master')

@section('content')
<div class="col-md-12 text-center" id="depositPaymob">
    <div class="mt-32 param-ref text-center">
        <div id="paymob-button-container">
            @foreach($types as $type)
                <div class="d-grid"><a href="{{ route('paymob.payment').'?currency_id='.$currency_id.'&method_id='.$method.'&paymob_payment_id='.$paymob_payment_id.'&paymob_payment_type='.$type }}" class='btn btn-primary'> Pay with {{$type}} </a></div>
            @endforeach
        </div>
    </div>
</div>
@endsection
@section('js')
{{--<script src="https://www.paypal.com/sdk/js?client-id={{ $authorizationKey }}&disable-funding=paylater&currency={{ $currencyCode }}"></script>--}}
<script>
    'use strict';
    var token = $('[name="_token"]').val();
    var types = JSON.parse('{!! json_encode($types) !!}');
    var redirect_url = "{!! route('paymob.payment') !!}"+"?params="+"{!! $params !!}"+"&currency_id="+"{!! $currency_id !!}"+"&method_id="+"{!! $method !!}"+"&paymob_payment_id="+"{!! $paymob_payment_id !!}"+"&paymob_payment_type=";
</script>
<script src="{{ asset('public/frontend/customs/js/gateways/paymob.js') }}"></script>
@endsection
