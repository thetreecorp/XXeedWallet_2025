<?php
/**
 * Copyright (c) 2022-2023 tatum.io
 * 
 * @link    https://tatumio.github.io/tatum-php/Api/BitcoinApi/#btctransactionfromaddress
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

$arg_btc_transaction_from_address = (new \Tatum\Model\BtcTransactionFromAddress())
    
    // The array of blockchain addresses to send the assets from and their private keys. For each addres...
    ->setFromAddress(null)
    
    // The array of blockchain addresses to send the assets to and the amounts that each address should ...
    ->setTo(null)
    
    // (optional) The fee to be paid for the transaction (in BTC); if you are using this parameter, you have to als...
    ->setFee('0.0015')
    
    // (optional) The blockchain address to send any extra assets remaning after covering the fee to; if you are us...
    ->setChangeAddress('2MzNGwuKvMEvKMQogtgzSqJcH2UW3Tc5oc7');

try {

    // 🐛 Enable debugging on the MainNet
    $sdk->mainnet()->config()->setDebug(true);

    /**
     * POST /v3/bitcoin/transaction
     * 
     * @var \Tatum\Model\TransactionSigned $response
     */
    $response = $sdk->mainnet()
        ->api()
        ->bitcoin()
        ->btcTransactionFromAddress($arg_btc_transaction_from_address);

    var_dump($response);

} catch (\Tatum\Sdk\ApiException $apiExc) {
    echo sprintf(
        "API Exception when calling api()->bitcoin()->btcTransactionFromAddress(): %s\n", 
        var_export($apiExc->getResponseObject(), true)
    );
} catch (\Exception $exc) {
    echo sprintf(
        "Exception when calling api()->bitcoin()->btcTransactionFromAddress(): %s\n", 
        $exc->getMessage()
    );
}