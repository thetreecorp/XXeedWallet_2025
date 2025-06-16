<?php
/**
 * Copyright (c) 2022-2023 tatum.io
 * 
 * @link    https://tatumio.github.io/tatum-php/Api/HarmonyApi/#callonesmartcontractmethodkms
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

$arg_call_one_smart_contract_method_kms = (new \Tatum\Model\CallOneSmartContractMethodKMS())
    
    // The address of the smart contract
    ->setContractAddress('0x687422eEA2cB73B5d3e242bA5456b782919AFc85')
    
    // Name of the method to invoke on smart contract.
    ->setMethodName('transfer')
    
    // ABI of the method to invoke.
    ->setMethodAbi(
        json_decode(
            'null'
        )
    )
    
    // Parameters of the method to be invoked.
    ->setParams(["0x632"])
    
    // (optional) If signatureId is mnemonic-based, this is the index to the specific address from that mnemonic.
    ->setIndex(null)
    
    // Identifier of the private key associated in signing application. Private key, or signature Id mus...
    ->setSignatureId('26d3883e-4e17-48b3-a0ee-09a3e484ac83')
    
    // (optional) Nonce to be set to ONE transaction. If not present, last known nonce will be used.
    ->setNonce(null)
    
    // (optional) \Tatum\Model\CustomFee
    ->setFee(null);

// Shard to read data from
$arg_shard_id = 0;

try {

    // 🐛 Enable debugging on the MainNet
    $sdk->mainnet()->config()->setDebug(true);

    /**
     * POST /v3/one/smartcontract
     * 
     * @var \Tatum\Model\CallSmartContractMethod200Response $response
     */
    $response = $sdk->mainnet()
        ->api()
        ->harmony()
        ->callOneSmartContractMethodKMS($arg_call_one_smart_contract_method_kms, $arg_shard_id);

    var_dump($response);

} catch (\Tatum\Sdk\ApiException $apiExc) {
    echo sprintf(
        "API Exception when calling api()->harmony()->callOneSmartContractMethodKMS(): %s\n", 
        var_export($apiExc->getResponseObject(), true)
    );
} catch (\Exception $exc) {
    echo sprintf(
        "Exception when calling api()->harmony()->callOneSmartContractMethodKMS(): %s\n", 
        $exc->getMessage()
    );
}