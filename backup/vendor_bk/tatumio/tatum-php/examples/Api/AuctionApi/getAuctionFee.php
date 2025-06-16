<?php
/**
 * Copyright (c) 2022-2023 tatum.io
 * 
 * @link    https://tatumio.github.io/tatum-php/Api/AuctionApi/#getauctionfee
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

// Blockchain to work with
$arg_chain = 'chain_example';

// Contract address
$arg_contract_address = "0xe6e7340394958674cdf8606936d292f565e4ecc4";

try {

    // 🐛 Enable debugging on the MainNet
    $sdk->mainnet()->config()->setDebug(true);

    /**
     * GET /v3/blockchain/auction/{chain}/{contractAddress}/fee
     * 
     * @var float $response
     */
    $response = $sdk->mainnet()
        ->api()
        ->auction()
        ->getAuctionFee($arg_chain, $arg_contract_address);

    var_dump($response);

} catch (\Tatum\Sdk\ApiException $apiExc) {
    echo sprintf(
        "API Exception when calling api()->auction()->getAuctionFee(): %s\n", 
        var_export($apiExc->getResponseObject(), true)
    );
} catch (\Exception $exc) {
    echo sprintf(
        "Exception when calling api()->auction()->getAuctionFee(): %s\n", 
        $exc->getMessage()
    );
}