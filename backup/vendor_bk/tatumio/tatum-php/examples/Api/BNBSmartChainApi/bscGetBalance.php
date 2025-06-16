<?php
/**
 * Copyright (c) 2022-2023 tatum.io
 * 
 * @link    https://tatumio.github.io/tatum-php/Api/BNBSmartChainApi/#bscgetbalance
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

// Account address you want to get balance of
$arg_address = "0x3223AEB8404C7525FcAA6C512f91e287AE9FfE7B";

try {

    // 🐛 Enable debugging on the MainNet
    $sdk->mainnet()->config()->setDebug(true);

    /**
     * GET /v3/bsc/account/balance/{address}
     * 
     * @var \Tatum\Model\BscBalance $response
     */
    $response = $sdk->mainnet()
        ->api()
        ->bNBSmartChain()
        ->bscGetBalance($arg_address);

    var_dump($response);

} catch (\Tatum\Sdk\ApiException $apiExc) {
    echo sprintf(
        "API Exception when calling api()->bNBSmartChain()->bscGetBalance(): %s\n", 
        var_export($apiExc->getResponseObject(), true)
    );
} catch (\Exception $exc) {
    echo sprintf(
        "Exception when calling api()->bNBSmartChain()->bscGetBalance(): %s\n", 
        $exc->getMessage()
    );
}