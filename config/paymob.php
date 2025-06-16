<?php

return [
    'AUTH_URL'=>'https://accept.paymob.com/api/auth/tokens',
    'PAYMENT_URL_CARD'=>'https://accept.paymob.com/api/ecommerce/payment-links',
    'PAYMENT_URL_WALLET'=>'https://accept.paymob.com/v1/intention/',
    'PUBLIC_PAYMENT_URL_BY_WALLET'=>'https://accept.paymob.com/unifiedcheckout/?publicKey=:public_key&clientSecret=:client_key',
    'PAYMENT_VERIFY_URL'=>'https://accept.paymob.com/api/acceptance/transactions/',
    'PAYMENT_TYPES'=>['card','wallet'],
    'CARD_PAYMENT_INTEGRATION_ID'=>266,
    'WALLET_PAYMENT_INTEGRATION_ID'=>4838482,
];
