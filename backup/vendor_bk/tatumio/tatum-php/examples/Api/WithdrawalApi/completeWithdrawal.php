<?php
/**
 * Copyright (c) 2022-2023 tatum.io
 * 
 * @link    https://tatumio.github.io/tatum-php/Api/WithdrawalApi/#completewithdrawal
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

// ID of created withdrawal
$arg_id = 'id_example';

// Blockchain transaction ID of created withdrawal
$arg_tx_id = 'tx_id_example';

try {

    // 🐛 Enable debugging on the MainNet
    $sdk->mainnet()->config()->setDebug(true);

    /**
     * PUT /v3/offchain/withdrawal/{id}/{txId}
     */
    $sdk->mainnet()
        ->api()
        ->withdrawal()
        ->completeWithdrawal($arg_id, $arg_tx_id);

} catch (\Tatum\Sdk\ApiException $apiExc) {
    echo sprintf(
        "API Exception when calling api()->withdrawal()->completeWithdrawal(): %s\n", 
        var_export($apiExc->getResponseObject(), true)
    );
} catch (\Exception $exc) {
    echo sprintf(
        "Exception when calling api()->withdrawal()->completeWithdrawal(): %s\n", 
        $exc->getMessage()
    );
}