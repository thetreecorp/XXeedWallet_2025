<?php
/**
 * Copyright (c) 2022-2023 tatum.io
 * 
 * @link    https://tatumio.github.io/tatum-php/Api/MultiTokensERC1155OrCompatibleApi/#multitokengetmetadata
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

// Token ID
$arg_token = "1";

// Multi Token contract address
$arg_contract_address = "0x94Ce79B9F001E25BBEbE7C01998A78F7B27D1326";

// Type of testnet. Defaults to Sepolia. Valid only for ETH invocations.
$arg_x_testnet_type = 'ethereum-sepolia';

try {

    // 🐛 Enable debugging on the MainNet
    $sdk->mainnet()->config()->setDebug(true);

    /**
     * GET /v3/multitoken/metadata/{chain}/{contractAddress}/{token}
     * 
     * @var \Tatum\Model\MultiTokenGetMetadata200Response $response
     */
    $response = $sdk->mainnet()
        ->api()
        ->multiTokensERC1155OrCompatible()
        ->multiTokenGetMetadata($arg_chain, $arg_token, $arg_contract_address, $arg_x_testnet_type);

    var_dump($response);

} catch (\Tatum\Sdk\ApiException $apiExc) {
    echo sprintf(
        "API Exception when calling api()->multiTokensERC1155OrCompatible()->multiTokenGetMetadata(): %s\n", 
        var_export($apiExc->getResponseObject(), true)
    );
} catch (\Exception $exc) {
    echo sprintf(
        "Exception when calling api()->multiTokensERC1155OrCompatible()->multiTokenGetMetadata(): %s\n", 
        $exc->getMessage()
    );
}