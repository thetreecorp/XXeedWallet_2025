<?php
/**
 * Copyright (c) 2022-2023 tatum.io
 * 
 * @link    https://tatumio.github.io/tatum-php/Api/TransactionApi/#gettransactionsbyaccountid
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

$arg_transaction_filter = (new \Tatum\Model\TransactionFilter())
    
    // Account ID - source of transaction(s).
    ->setId('5e6be8e9e6aa436299950c41')
    
    // (optional) Counter account - transaction(s) destination account.
    ->setCounterAccount('5e6be8e9e6aa436299950c41')
    
    // (optional) Starting date to search for transactions from in UTC millis. If not present, search all history.
    ->setFrom(1571833231000)
    
    // (optional) Date until to search for transactions in UTC millis. If not present, search up till now.
    ->setTo(1571833231000)
    
    // (optional) Currency of the transactions.
    ->setCurrency('BTC')
    
    // (optional) Amount of the transaction. AND is used between filter options.
    ->setAmount(null)
    
    // (optional) List of currencies of the transactions.
    ->setCurrencies(null)
    
    // (optional) Type of payment
    ->setTransactionType('null')
    
    // (optional) Types of payment
    ->setTransactionTypes(null)
    
    // (optional) Type of operation
    ->setOpType('PAYMENT')
    
    // (optional) For bookkeeping to distinct transaction purpose.
    ->setTransactionCode('1_01_EXTERNAL_CODE')
    
    // (optional) Payment ID defined in payment order by sender.
    ->setPaymentId('65426')
    
    // (optional) Recipient note defined in payment order by sender.
    ->setRecipientNote('65426')
    
    // (optional) Sender note defined in payment order by sender.
    ->setSenderNote('65426');

// Max number of items per page is 50. Either count or pageSize is accepted.
$arg_page_size = 10;

// Offset to obtain the next page of data.
$arg_offset = 0;

// Get the total transaction count based on the filter. Either count or pageSize is accepted.
$arg_count = true;

try {

    // 🐛 Enable debugging on the MainNet
    $sdk->mainnet()->config()->setDebug(true);

    /**
     * POST /v3/ledger/transaction/account
     * 
     * @var \Tatum\Model\GetTransactionsByAccountId200Response $response
     */
    $response = $sdk->mainnet()
        ->api()
        ->transaction()
        ->getTransactionsByAccountId($arg_transaction_filter, $arg_page_size, $arg_offset, $arg_count);

    var_dump($response);

} catch (\Tatum\Sdk\ApiException $apiExc) {
    echo sprintf(
        "API Exception when calling api()->transaction()->getTransactionsByAccountId(): %s\n", 
        var_export($apiExc->getResponseObject(), true)
    );
} catch (\Exception $exc) {
    echo sprintf(
        "Exception when calling api()->transaction()->getTransactionsByAccountId(): %s\n", 
        $exc->getMessage()
    );
}