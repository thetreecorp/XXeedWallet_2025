<?php
/**
 * Copyright (c) 2022-2023 tatum.io
 * 
 * @link    https://tatumio.github.io/tatum-php/Api/BlockchainOperationsApi/#offonedeployerc20offchainmnemxpub
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

$arg_deploy_erc20_offchain_mnem_xpub = (new \Tatum\Model\DeployErc20OffchainMnemXpub())
    
    // The name of the token; used as an identifier within the Tatum platform and as a currency symbol o...
    ->setSymbol('MY_TOKEN')
    
    // The supply of the token
    ->setSupply('10000000')
    
    // The description of the token; used as a description within the Tatum platform and as a currency n...
    ->setDescription('My Public Token')
    
    // The extended public key from which a deposit address for the virtual account will be generated
    ->setXpub('xpub6EsCk1uU6cJzqvP9CdsTiJwT2rF748YkPnhv5Qo8q44DG7nn2vbyt48YRsNSUYS44jFCW9gwvD9kLQu9AuqXpTpM1c5hgg9PsuBLdeNncid')
    
    // The derivation index to use together with the extended public key to generate the deposit address
    ->setDerivationIndex(0)
    
    // The mnemonic to generate the private key for the blockchain address from which the fee for deploy...
    ->setMnemonic('urge pulp usage sister evidence arrest palm math please chief egg abuse')
    
    // The derivation index of the blockchain address from which the fee for deploying the smart contrac...
    ->setIndex(0)
    
    // The base pair for the virtual currency that represents the token; used to calculate the value of ...
    ->setBasePair('EUR')
    
    // (optional) The exchange rate for the base pair; one unit of the created virtual currency equals 1 unit of <c...
    ->setBaseRate(1)
    
    // (optional) The nonce to be set to the transaction; if not present, the last known nonce will be used
    ->setNonce(null)
    
    // (optional) \Tatum\Model\CustomerRegistration
    ->setCustomer(null);

// The ID of the shard to operate
$arg_shard_id = 0;

try {

    // 🐛 Enable debugging on the MainNet
    $sdk->mainnet()->config()->setDebug(true);

    /**
     * POST /v3/offchain/one/hrm20/deploy
     * 
     * @var \Tatum\Model\DeployErc20OffchainMnemonicAddress200Response $response
     */
    $response = $sdk->mainnet()
        ->api()
        ->blockchainOperations()
        ->offOneDeployErc20OffchainMnemXpub($arg_deploy_erc20_offchain_mnem_xpub, $arg_shard_id);

    var_dump($response);

} catch (\Tatum\Sdk\ApiException $apiExc) {
    echo sprintf(
        "API Exception when calling api()->blockchainOperations()->offOneDeployErc20OffchainMnemXpub(): %s\n", 
        var_export($apiExc->getResponseObject(), true)
    );
} catch (\Exception $exc) {
    echo sprintf(
        "Exception when calling api()->blockchainOperations()->offOneDeployErc20OffchainMnemXpub(): %s\n", 
        $exc->getMessage()
    );
}