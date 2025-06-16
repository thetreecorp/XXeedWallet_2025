<?php
/**
 * Copyright (c) 2022-2023 tatum.io
 * 
 * @link    https://tatumio.github.io/tatum-php/Api/PolygonApi/#polygongeneratewallet
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

// Mnemonic to use for generation of extended public and private keys.
$arg_mnemonic = 'police hat quantum advance enroll glove thank build warfare inject invite obscure enable inflict seek web cable answer chest suspect comfort tent source seven';

try {

    // 🐛 Enable debugging on the MainNet
    $sdk->mainnet()->config()->setDebug(true);

    /**
     * GET /v3/polygon/wallet
     * 
     * @var \Tatum\Model\Wallet $response
     */
    $response = $sdk->mainnet()
        ->api()
        ->polygon()
        ->polygonGenerateWallet($arg_mnemonic);

    var_dump($response);

} catch (\Tatum\Sdk\ApiException $apiExc) {
    echo sprintf(
        "API Exception when calling api()->polygon()->polygonGenerateWallet(): %s\n", 
        var_export($apiExc->getResponseObject(), true)
    );
} catch (\Exception $exc) {
    echo sprintf(
        "Exception when calling api()->polygon()->polygonGenerateWallet(): %s\n", 
        $exc->getMessage()
    );
}