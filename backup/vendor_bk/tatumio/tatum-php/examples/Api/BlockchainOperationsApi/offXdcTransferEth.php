<?php
/**
 * Copyright (c) 2022-2023 tatum.io
 * 
 * @link    https://tatumio.github.io/tatum-php/Api/BlockchainOperationsApi/#offxdctransfereth
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

$arg_transfer_eth = (new \Tatum\Model\TransferEth())
    
    // (optional) The nonce to be set to the transaction; if not present, the last known nonce will be used
    ->setNonce(null)
    
    // Blockchain address to send assets
    ->setAddress('0x687422eEA2cB73B5d3e242bA5456b782919AFc85')
    
    // Amount to be sent in Ether / MATIC / XDC / KCS.
    ->setAmount('100000')
    
    // (optional) Compliance check, if withdrawal is not compliant, it will not be processed.
    ->setCompliant(false)
    
    // Private key of sender address. Either mnemonic and index, privateKey or signature Id must be pres...
    ->setPrivateKey('0x687422eEA2cB73B5d3e242bA5456b782919AFc85')
    
    // (optional) Identifier of the payment, shown for created Transaction within Tatum sender account.
    ->setPaymentId('1234')
    
    // Sender account ID
    ->setSenderAccountId('5e68c66581f2ee32bc354087')
    
    // (optional) Note visible to owner of withdrawing account
    ->setSenderNote('Sender note')
    
    // (optional) Gas limit for transaction in gas price. If not set, automatic calculation will be used.
    ->setGasLimit('40000')
    
    // (optional) Gas price in Gwei. If not set, automatic calculation will be used.
    ->setGasPrice('20');

try {

    // 🐛 Enable debugging on the MainNet
    $sdk->mainnet()->config()->setDebug(true);

    /**
     * POST /v3/offchain/xdc/transfer
     * 
     * @var \Tatum\Model\TransferBtcMnemonic200Response $response
     */
    $response = $sdk->mainnet()
        ->api()
        ->blockchainOperations()
        ->offXdcTransferEth($arg_transfer_eth);

    var_dump($response);

} catch (\Tatum\Sdk\ApiException $apiExc) {
    echo sprintf(
        "API Exception when calling api()->blockchainOperations()->offXdcTransferEth(): %s\n", 
        var_export($apiExc->getResponseObject(), true)
    );
} catch (\Exception $exc) {
    echo sprintf(
        "Exception when calling api()->blockchainOperations()->offXdcTransferEth(): %s\n", 
        $exc->getMessage()
    );
}