<?php
/**
 * Copyright (c) 2022-2023 tatum.io
 * 
 * @link    https://tatumio.github.io/tatum-php/Api/VeChainApi/#vetgetbalance
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
$arg_address = "0x5034aa590125b64023a0262112b98d72e3c8e40e";

try {

    // 🐛 Enable debugging on the MainNet
    $sdk->mainnet()->config()->setDebug(true);

    /**
     * GET /v3/vet/account/balance/{address}
     * 
     * @var \Tatum\Model\VetGetBalance200Response $response
     */
    $response = $sdk->mainnet()
        ->api()
        ->veChain()
        ->vetGetBalance($arg_address);

    var_dump($response);

} catch (\Tatum\Sdk\ApiException $apiExc) {
    echo sprintf(
        "API Exception when calling api()->veChain()->vetGetBalance(): %s\n", 
        var_export($apiExc->getResponseObject(), true)
    );
} catch (\Exception $exc) {
    echo sprintf(
        "Exception when calling api()->veChain()->vetGetBalance(): %s\n", 
        $exc->getMessage()
    );
}