<?php
/**
 * Copyright (c) 2022-2023 tatum.io
 * 
 * @link    https://tatumio.github.io/tatum-php/Api/KlaytnApi/#transferklaytnblockchainkms
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

$arg_transfer_klaytn_blockchain_kms = (new \Tatum\Model\TransferKlaytnBlockchainKMS())
    
    // (optional) Additional data that can be passed to a blockchain transaction as a data property; must be in the...
    ->setData('4d79206e6f746520746f2074686520726563697069656e74')
    
    // (optional) Nonce to be set to Klaytn transaction. If not present, last known nonce will be used.
    ->setNonce(null)
    
    // Blockchain address to send assets
    ->setTo('0x687422eEA2cB73B5d3e242bA5456b782919AFc85')
    
    // Currency to transfer from Klaytn Blockchain Account.
    ->setCurrency('KLAY')
    
    // (optional) \Tatum\Model\TransferKlay20BlockchainFee
    ->setFee(null)
    
    // Amount to be sent in Ether.
    ->setAmount('100000')
    
    // (optional) If signatureId is mnemonic-based, this is the index to the specific address from that mnemonic.
    ->setIndex(null)
    
    // Identifier of the private key associated in signing application. Private key, or signature Id mus...
    ->setSignatureId('26d3883e-4e17-48b3-a0ee-09a3e484ac83');

try {

    // 🐛 Enable debugging on the MainNet
    $sdk->mainnet()->config()->setDebug(true);

    /**
     * POST /v3/klaytn/transaction
     * 
     * @var \Tatum\Model\TransactionSigned $response
     */
    $response = $sdk->mainnet()
        ->api()
        ->klaytn()
        ->transferKlaytnBlockchainKMS($arg_transfer_klaytn_blockchain_kms);

    var_dump($response);

} catch (\Tatum\Sdk\ApiException $apiExc) {
    echo sprintf(
        "API Exception when calling api()->klaytn()->transferKlaytnBlockchainKMS(): %s\n", 
        var_export($apiExc->getResponseObject(), true)
    );
} catch (\Exception $exc) {
    echo sprintf(
        "Exception when calling api()->klaytn()->transferKlaytnBlockchainKMS(): %s\n", 
        $exc->getMessage()
    );
}