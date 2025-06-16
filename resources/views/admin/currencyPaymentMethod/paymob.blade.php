
<!-- paymob - Authorization Key -->
<div class="form-group row">
    <label class="col-sm-3 control-label mt-11 f-14 fw-bold text-md-end" for="payeer_user_name">{{ __('Paymob User Name') }}</label>
    <div class="col-sm-6">
        <input class="form-control f-14 paymob[user_name]" name="paymob[user_name]" type="text" placeholder="{{ __('Paymob User Name') }}"
               value="{{ isset($currencyPaymentMethod->method_data) ? json_decode($currencyPaymentMethod->method_data)->user_name : '' }}" id="paymob_user_name">
        @if ($errors->has('paymob[user_name]'))
            <span class="help-block">
                <strong>{{ $errors->first('paymob[user_name]') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 control-label mt-11 f-14 fw-bold text-md-end" for="payeer_password">{{ __('Paymob Password') }}</label>
    <div class="col-sm-6">
        <input class="form-control f-14 paymob[password]" name="paymob[password]" type="text" placeholder="{{ __('Paymob Password') }}"
        value="{{ isset($currencyPaymentMethod->method_data) ? json_decode($currencyPaymentMethod->method_data)->password : '' }}" id="paymob_password">
        @if ($errors->has('paymob[password]'))
            <span class="help-block">
                    <strong>{{ $errors->first('paymob[password]') }}</strong>
                </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 control-label mt-11 f-14 fw-bold text-md-end" for="payeer_api_key">{{ __('Paymob Secret API Key') }}</label>
    <div class="col-sm-6">
        <input class="form-control f-14 paymob[api_key]" name="paymob[api_key]" type="text" placeholder="{{ __('Paymob API Key') }}"
        value="{{ isset($currencyPaymentMethod->method_data) ? json_decode($currencyPaymentMethod->method_data)->api_key : '' }}" id="paymob_api_key">
        @if ($errors->has('paymob[api_key]'))
            <span class="help-block">
                    <strong>{{ $errors->first('paymob[api_key]') }}</strong>
                </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 control-label mt-11 f-14 fw-bold text-md-end" for="payeer_public_api_key">{{ __('Paymob Public API Key') }}</label>
    <div class="col-sm-6">
        <input class="form-control f-14 paymob[public_key]" name="paymob[public_key]" type="text" placeholder="{{ __('Paymob API Key') }}"
        value="{{ isset($currencyPaymentMethod->method_data) ? json_decode($currencyPaymentMethod->method_data)->public_key : '' }}" id="paymob_public_api_key">
        @if ($errors->has('paymob[public_key]'))
            <span class="help-block">
                    <strong>{{ $errors->first('paymob[public_key]') }}</strong>
                </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 control-label mt-11 f-14 fw-bold text-md-end" for="paypal_mode">{{ __('Mode') }}</label>
    <div class="col-sm-6">
        <select class="form-control f-14" name="paymob[mode]" id="paymob_mode">
            <option value="">{{ __('Select Mode') }}</option>
            <option value='sandbox' {{ isset($currencyPaymentMethod->method_data) && (json_decode($currencyPaymentMethod->method_data)->mode) == 'sandbox' ? 'selected':"" }} >{{ __('sandbox') }}</option>
            <option value='live' {{ isset($currencyPaymentMethod->method_data) && (json_decode($currencyPaymentMethod->method_data)->mode) == 'live' ? 'selected':"" }} >{{ __('live') }}</option>
        </select>
    </div>
</div>
<div class="clearfix"></div>

<div class="clearfix"></div>
