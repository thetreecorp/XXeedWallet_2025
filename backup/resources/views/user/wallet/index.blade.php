@extends('user.layouts.app')
@push('css')
<style>
   
    .tooltip-cs {
        position: relative;
        display: inline-block;
    }
    
    .copy-text {
        border: none;
        background: transparent;
        margin-top: 7px;
    }
    
    .tooltip-cs .tooltiptext {
        visibility: hidden;
        width: 240px;
        background-color: #555;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        bottom: 100%;
        left: 50%;
        margin-left: -120px;
    }
    
    .tooltip-cs .tooltiptext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #555 transparent transparent transparent;
    }
    
    .tooltip-cs:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }
</style>
@endpush
@section('content')
<div class="pb-34">
    <div class="px-61 pb-20 helper-size">
      <p class="mb-0 f-26 gilroy-Semibold text-uppercase text-center text-dark">{{ __('Wallet List') }}</p>
      <p class="mb-0 text-center f-16 leading-26 gilroy-medium text-gray-100 dark-c dark-p mt-8">{{ __('Here you will get all of your Fiat and Crypto wallets including default one. You can also perform crypto send/receive of your crypto coins.') }}</p>
    </div>
    <div class="px-28 helper-div">
        <div class="row r-mt-n">
            @if($wallets->count() > 0)
                @foreach ($wallets as $wallet)
                    @php
                        $walletCurrencyCode = encrypt(optional($wallet->currency)->code);
                        $walletId = encrypt($wallet->id);
                        $provider = isset($wallet->cryptoAssetApiLogs->payment_method->name) && !empty($wallet->cryptoAssetApiLogs->payment_method->name) ? strtolower($wallet->cryptoAssetApiLogs->payment_method->name): '';
                    @endphp
                    <div class="col-12 col-xl-6 mt-19">
                        <div class="balance-box">
                            <div class="d-flex justify-content-between">
                                <div class="wallet-left-box d-flex gap-18">
                                    <div class="curency-box d-flex align-items-center justify-content-center">
                                        <img src="{{ image($wallet->currency?->logo, 'currency') }}" alt="{{ __('Currency') }}">             
                                    </div>
                                    <div class="mt-n3p span-currency">
                                        <span class="f-15 gilroy-medium text-gray">{{ ucwords(str_replace('_', ' ', $wallet->currency?->type)) }}</span>
                                        <p class="mb-0 mt-6"><span class="f-28 gilroy-Semibold text-dark">{{ $wallet->currency?->code }}</span><span class="ml-2p f-15 text-primary gilroy-medium">{{ $wallet->is_default == 'Yes' ? '(default)' : '' }}</span></p>
                                    </div>
                                </div>
                                <div class="wallet-right-box mt-n3p span-currency text-end">
                                    <span class="f-15 gilroy-medium text-gray">{{ __('Balance') }}</span>
                                    <p class="mb-0 mt-6 f-28 gilroy-Semibold text-dark l-s2">{{ formatNumber($wallet->balance, $wallet->currency?->id) }}</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between span-currency">
                                <div class="currency-mt-32">
                                    @php
                                        $lastTransaction = \App\Models\Transaction::with('transaction_type')->where('currency_id', $wallet->currency?->id)->where(function($query){
                                            $query->where('user_id', auth()->id())->orWhere('end_user_id', auth()->id());
                                        })->latest()->first();
                                    @endphp
                                    @if (is_null($lastTransaction)) 
                                        <p class="text-gray mb-0 f-12 leading-16 gilroy-medium">{{ __('Last Action') }}: 
                                            <span class="text-dark">{{ __('No transaction available.') }}</span> 
                                        </p>
                                    @else
                                        <p class="text-gray mb-0 f-12 leading-16 gilroy-medium">{{ __('Last Action') }}: 
                                            <span class="text-dark">{{ moneyFormat($wallet->currency->symbol, formatNumber($lastTransaction->subtotal)) }}</span> 
                                            @php
                                                if (!empty($lastTransaction)) {
                                                    if ($lastTransaction->transaction_type->name == 'Transferred') {
                                                        $transactionName = 'Money Transfer';
                                                    } elseif ($lastTransaction->transaction_type->name == 'Received') {
                                                        $transactionName = 'Money Received';
                                                    } elseif ($lastTransaction->transaction_type->name == 'Exchange_From' || $lastTransaction->transaction_type->name == 'Exchange_To') {
                                                        $transactionName = 'Money Exchange';
                                                    } elseif ($lastTransaction->transaction_type->name == 'Request_Sent' || $lastTransaction->transaction_type->name == 'Request_Received') {
                                                        $transactionName = 'Request Money';
                                                    } else {
                                                        if (str_contains($lastTransaction->transaction_type->name, '_')) {
                                                            $transactionName = str_replace('_', ' ', $lastTransaction->transaction_type->name);
                                                        } else {
                                                            $transactionName = $lastTransaction->transaction_type->name;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            ( {{ $transactionName }} )
                                        </p>
                                    @endif
                                </div>
                                <div class="right-icon-div d-flex">
                                    <div class="btn-block d-flex mt-20">

                                        @if (($wallet->currency->type == 'crypto' || $wallet->currency->type == 'fiat') && $wallet->currency->status == 'Active')
                                            @if(Common::has_permission(auth()->id(), 'manage_deposit'))

                                                <div class="d-flex flex-wrap pt-5p wallet-svg show-tooltip" data-bs-toggle="tooltip" data-color="primary-bottom" data-bs-placement="bottom" title="deposit">
                                                    <a href="{{ route('user.deposit.create') }}" title="{{ __('deposit') }}">
                                                        {!! svgIcons('wallet_arrow_down') !!}
                                                    </a>
                                                </div>
                                            @endif
                                            {!! Common::has_permission(auth()->id(),'manage_deposit') && Common::has_permission(auth()->id(),'manage_withdrawal') ? '<div class="hr-40"></div>' : '' !!}

                                            @if(Common::has_permission(auth()->id(),'manage_withdrawal'))
                                                <div class="d-flex flex-wrap pt-5p wallet-svg show-tooltip" data-bs-toggle="tooltip" data-color="primary-bottom" data-bs-placement="bottom" title="{{ __('withdraw') }}">
                                                    <a href="{{ route('user.withdrawal.create') }}" class="mt-1p" >
                                                        {!! svgIcons('wallet_arrow_up') !!}
                                                    </a>
                                                </div>
                                            @endif

                                        @elseif (optional($wallet->currency)->type == 'crypto_asset' && $provider_status == 'Active' && isActive('BlockIo') && (optional($wallet->cryptoAssetApiLogs)->payment_method_id == BlockIo))
                                            @if(Common::has_permission(auth()->id(),'manage_crypto_send_receive'))
                                                <div class="d-flex flex-wrap pt-5p wallet-svg show-tooltip" data-bs-toggle="tooltip" data-color="primary-bottom" data-bs-placement="bottom" title="{{ __('Crypto Send') }}">
                                                    <a href="{{ route('user.crypto_send.create', [$walletCurrencyCode, $walletId, $provider]) }}" title="deposit">
                                                        {!! svgIcons('wallet_arrow_up') !!}
                                                    </a>
                                                </div>
                                                <div class="hr-40"></div>
                                                <div class="d-flex flex-wrap pt-5p wallet-svg show-tooltip" data-bs-toggle="tooltip" data-color="primary-bottom" data-bs-placement="bottom" title="{{ __('Crypto Receive') }}">
                                                    <a href="{{ route('user.crypto_receive.create', [$walletCurrencyCode, $walletId, $provider]) }}" class="mt-1p" >
                                                        {!! svgIcons('wallet_arrow_down') !!}
                                                    </a>
                                                </div>
                                            @endif
                                        @elseif (optional($wallet->currency)->type == 'crypto_asset' && $tatum_provider_status == 'Active' && isActive('TatumIo') && (optional($wallet->cryptoAssetApiLogs)->payment_method_id == TatumIo) && (optional($wallet->currency)->status == 'Active'))
                                            @if(Common::has_permission(auth()->id(),'manage_crypto_send_receive'))
                                                <div class="d-flex flex-wrap pt-5p wallet-svg show-tooltip" data-bs-toggle="tooltip" data-color="primary-bottom" data-bs-placement="bottom" title="{{ __('Crypto Send') }}">
                                                    <a href="{{ route('tatumio.user.crypto_send.create', [$walletCurrencyCode, $walletId, $provider]) }}" title="deposit">
                                                        {!! svgIcons('wallet_arrow_up') !!}
                                                    </a>
                                                </div>
                                                <div class="hr-40"></div>
                                                <div class="d-flex flex-wrap pt-5p wallet-svg show-tooltip" data-bs-toggle="tooltip" data-color="primary-bottom" data-bs-placement="bottom" title="{{ __('Crypto Receive') }}">
                                                    <a href="{{ route('tatumio.user.crypto_receive.create', [$walletCurrencyCode, $walletId, $provider]) }}" class="mt-1p" >
                                                        {!! svgIcons('wallet_arrow_down') !!}
                                                    </a>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
            @if($near_wallet)
                <div class="col-12 col-xl-6 mt-19">
                    <div class="balance-box">
                        <div class="d-flex justify-content-between">
                            <div class="wallet-left-box d-flex gap-18">
                                <div class="curency-box d-flex align-items-center justify-content-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 414 162" class="near-logo">
                                    <g id="Layer_1" data-name="Layer 1">
                                        <path d="M207.21,54.75v52.5a.76.76,0,0,1-.75.75H201a7.49,7.49,0,0,1-6.3-3.43l-24.78-38.3.85,19.13v21.85a.76.76,0,0,1-.75.75h-7.22a.76.76,0,0,1-.75-.75V54.75a.76.76,0,0,1,.75-.75h5.43a7.52,7.52,0,0,1,6.3,3.42l24.78,38.24-.77-19.06V54.75a.75.75,0,0,1,.75-.75h7.22A.76.76,0,0,1,207.21,54.75Z" style="fill: rgb(63 57 57);"></path>
                                        <path d="M281,108h-7.64a.75.75,0,0,1-.7-1L292.9,54.72A1.14,1.14,0,0,1,294,54h9.57a1.14,1.14,0,0,1,1.05.72L324.8,107a.75.75,0,0,1-.7,1h-7.64a.76.76,0,0,1-.71-.48l-16.31-43a.75.75,0,0,0-1.41,0l-16.31,43A.76.76,0,0,1,281,108Z" style="fill: rgb(52 47 47);"></path>
                                        <path d="M377.84,106.79,362.66,87.4c8.57-1.62,13.58-7.4,13.58-16.27,0-10.19-6.63-17.13-18.36-17.13H336.71a1.12,1.12,0,0,0-1.12,1.12h0a7.2,7.2,0,0,0,7.2,7.2H357c7.09,0,10.49,3.63,10.49,8.87s-3.32,9-10.49,9H336.71a1.13,1.13,0,0,0-1.12,1.13v26a.75.75,0,0,0,.75.75h7.22a.76.76,0,0,0,.75-.75V87.87h8.33l13.17,17.19a7.51,7.51,0,0,0,6,2.94h5.48A.75.75,0,0,0,377.84,106.79Z" style="fill: rgb(57 51 51);"></path>
                                        <path d="M258.17,54h-33.5a1,1,0,0,0-1,1h0A7.33,7.33,0,0,0,231,62.33h27.17a.74.74,0,0,0,.75-.75V54.75A.75.75,0,0,0,258.17,54Zm0,45.67h-25a.76.76,0,0,1-.75-.75V85.38a.75.75,0,0,1,.75-.75h23.11a.75.75,0,0,0,.75-.75V77a.75.75,0,0,0-.75-.75H224.79a1.13,1.13,0,0,0-1.12,1.13v29.45a1.12,1.12,0,0,0,1.12,1.13h33.38a.75.75,0,0,0,.75-.75v-6.83A.74.74,0,0,0,258.17,99.67Z" style="fill: rgb(42 39 39);"></path>
                                        <path d="M108.24,40.57,89.42,68.5a2,2,0,0,0,3,2.63l18.52-16a.74.74,0,0,1,1.24.56v50.29a.75.75,0,0,1-1.32.48l-56-67A9.59,9.59,0,0,0,47.54,36H45.59A9.59,9.59,0,0,0,36,45.59v70.82A9.59,9.59,0,0,0,45.59,126h0a9.59,9.59,0,0,0,8.17-4.57L72.58,93.5a2,2,0,0,0-3-2.63l-18.52,16a.74.74,0,0,1-1.24-.56V56.07a.75.75,0,0,1,1.32-.48l56,67a9.59,9.59,0,0,0,7.33,3.4h2a9.59,9.59,0,0,0,9.59-9.59V45.59A9.59,9.59,0,0,0,116.41,36h0A9.59,9.59,0,0,0,108.24,40.57Z" style="fill: rgb(36 33 33);"></path>
                                    </g>
                                </svg>
                                </div>
                                <div class="mt-n3p span-currency">
                                    <span class="f-15 gilroy-medium text-gray">wallet</span>
                                    <p class="mb-0 mt-6"><span class="f-28 gilroy-Semibold text-dark">NEAR</span><span class="ml-2p f-15 text-primary gilroy-medium"></span></p>
                                </div>
                            </div>
                            <div class="wallet-right-box mt-n3p span-currency text-end">
                                
                            </div>
                        </div>
                        <div class="d-flex justify-content-between span-currency">
                            <div class="currency-mt-32">
                                <label id="near_label">{{$near_wallet}}</label>
                            </div>
                            <div class="right-icon-div d-flex">
                                <div class="btn-block d-flex mt-20">
                                    <div class="d-flex flex-wrap pt-5p wallet-svg show-tooltip" data-bs-toggle="tooltip" data-color="primary-bottom" data-bs-placement="bottom" title="" data-bs-original-title="deposit">
                                       
                                    </div>
                                   
                                        
                                    <div class="d-flex flex-wrap pt-5p wallet-svg " data-color="primary-bottom" data-bs-placement="bottom" title="">
                                        <div class="tooltip-cs">
                                            <button class="copy-text" onclick="myFunction()" onmouseout="outFunc()">
                                                <span class="tooltiptext" id="myTooltip">Copy to clipboard</span>
                                                <svg class="cursor-pointer deposit" width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.2534 6.9231H25.7478C26.345 6.92308 26.8601 6.92306 27.2845 6.95837C27.7324 6.99564 28.1778 7.07793 28.6065 7.30039C29.246 7.63225 29.7659 8.1618 30.0917 8.81313C30.3101 9.24972 30.3909 9.70344 30.4275 10.1596C30.4622 10.5918 30.4622 11.1165 30.4621 11.7248V16.1539C30.4621 16.7911 29.9549 17.3077 29.3293 17.3077C28.7036 17.3077 28.1964 16.7911 28.1964 16.1539V15H7.8048V20.5385C7.8048 21.2037 7.80568 21.6333 7.8319 21.9602C7.85705 22.2737 7.89973 22.3899 7.92827 22.4469C8.03689 22.664 8.21019 22.8406 8.42335 22.9512C8.47937 22.9802 8.59347 23.0237 8.90123 23.0493C9.22221 23.076 9.64395 23.0769 10.2971 23.0769H18.0006C18.6263 23.0769 19.1335 23.5935 19.1335 24.2308C19.1335 24.868 18.6263 25.3846 18.0006 25.3846H10.2535C9.65626 25.3847 9.14107 25.3847 8.71672 25.3494C8.26885 25.3121 7.82339 25.2298 7.39473 25.0073C6.75525 24.6755 6.23533 24.1459 5.90949 23.4946C5.69108 23.058 5.61029 22.6043 5.5737 22.1481C5.53903 21.7159 5.53904 21.1912 5.53906 20.583V11.7248C5.53904 11.1165 5.53903 10.5918 5.5737 10.1596C5.61029 9.70344 5.69108 9.24972 5.90949 8.81313C6.23533 8.1618 6.75525 7.63225 7.39473 7.30038C7.82339 7.07793 8.26885 6.99564 8.71673 6.95837C9.14107 6.92306 9.65625 6.92308 10.2534 6.9231ZM7.8048 12.6923H28.1964V11.7693C28.1964 11.104 28.1955 10.6745 28.1693 10.3475C28.1442 10.0341 28.1015 9.91785 28.0729 9.8608C27.9643 9.6437 27.791 9.46718 27.5778 9.35655C27.5218 9.32748 27.4077 9.28401 27.1 9.2584C26.779 9.23169 26.3573 9.23079 25.7041 9.23079H10.2971C9.64395 9.23079 9.22221 9.23169 8.90123 9.2584C8.59347 9.28401 8.47937 9.32748 8.42336 9.35655C8.21019 9.46717 8.03689 9.64369 7.92827 9.8608C7.89973 9.91785 7.85705 10.0341 7.8319 10.3475C7.80568 10.6745 7.8048 11.104 7.8048 11.7693V12.6923ZM25.9307 18.4616C26.5563 18.4616 27.0635 18.9782 27.0635 19.6154V23.7528L28.5282 22.2611C28.9706 21.8104 29.6879 21.8104 30.1303 22.2611C30.5727 22.7117 30.5727 23.4422 30.1303 23.8928L26.7317 27.3544C26.2893 27.805 25.572 27.805 25.1296 27.3544L21.731 23.8928C21.2886 23.4422 21.2886 22.7117 21.731 22.2611C22.1734 21.8104 22.8907 21.8104 23.3331 22.2611L24.7978 23.7529V19.6154C24.7978 18.9782 25.305 18.4616 25.9307 18.4616Z" fill="currentColor"></path>
                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endif
        </div>
    </div>
</div>
@endsection

@push('js')
    <script type="text/javascript">
        
        function myFunction() {
          var copyText = document.getElementById("near_label").textContent;
          navigator.clipboard.writeText(copyText);
          
          var tooltip = document.getElementById("myTooltip");
          tooltip.innerHTML = "Copied: " + copyText;
        }
        
        function outFunc() {
          var tooltip = document.getElementById("myTooltip");
          tooltip.innerHTML = "Copy to clipboard";
        }
    </script>

@endpush