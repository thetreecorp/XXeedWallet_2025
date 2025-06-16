<?php
/**
 * Copyright (c) 2022-2023 tatum.io
 * 
 * @link    https://tatumio.github.io/tatum-php/Api/AccountApi/#unblockamountwithtransaction
 * @license MIT
 * @author  Mark Jivko
 * 
 * SECURITY WARNING
 * Execute this file in CLI mode only!
 */
"cli" !== php_sapi_name() && exit();

// Use any PSR-4 autoloader
require_once dirname(__DIR__, 3) . "/autoload.php";

// Set your API Keys 👇 here
$sdk = new \Tatum\Sdk();

// Blockage ID
$arg_id = "5e6be89ee6aa436299950c3f";

$arg_unblock_amount = (new \Tatum\Model\UnblockAmount())
    
    // Internal recipient account ID within Tatum platform
    ->setRecipientAccountId('5e6645712b55823de7ea82f2')
    
    // Amount to be sent. Amount can be smaller then the blocked amount.
    ->setAmount('5')
    
    // (optional) Anonymous transaction does not show sender account to recipient, default is false
    ->setAnonymous(false)
    
    // (optional) Enable compliant checks. Transaction will not be processed, if compliant check fails.
    ->setCompliant(false)
    
    // (optional) For bookkeeping to distinct transaction purpose.
    ->setTransactionCode('1_01_EXTERNAL_CODE')
    
    // (optional) Payment ID, External identifier of the payment, which can be used to pair transactions within Tat...
    ->setPaymentId('9625')
    
    // (optional) Note visible to both, sender and recipient
    ->setRecipientNote('Private note')
    
    // (optional) Exchange rate of the base pair. Only applicable for Tatum's Virtual currencies Ledger transaction...
    ->setBaseRate(1)
    
    // (optional) Note visible to sender
    ->setSenderNote('Sender note');

try {

    // 🐛 Enable debugging on the MainNet
    $sdk->mainnet()->config()->setDebug(true);

    /**
     * PUT /v3/ledger/account/block/{id}
     * 
     * @var \Tatum\Model\TransactionResult $response
     */
    $response = $sdk->mainnet()
        ->api()
        ->account()
        ->unblockAmountWithTransaction($arg_id, $arg_unblock_amount);

    var_dump($response);

} catch (\Tatum\Sdk\ApiException $apiExc) {
    echo sprintf(
        "API Exception when calling api()->account()->unblockAmountWithTransaction(): %s\n", 
        var_export($apiExc->getResponseObject(), true)
    );
} catch (\Exception $exc) {
    echo sprintf(
        "Exception when calling api()->account()->unblockAmountWithTransaction(): %s\n", 
        $exc->getMessage()
    );
}