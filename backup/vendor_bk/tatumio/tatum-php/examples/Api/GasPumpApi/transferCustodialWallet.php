<?php
/**
 * Copyright (c) 2022-2023 tatum.io
 * 
 * @link    https://tatumio.github.io/tatum-php/Api/GasPumpApi/#transfercustodialwallet
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

$arg_transfer_custodial_wallet = (new \Tatum\Model\TransferCustodialWallet())
    
    // The blockchain to work with
    ->setChain('ETH')
    
    // The gas pump address that transfers the asset; this is the address that you precalculated and act...
    ->setCustodialAddress('0x687422eEA2cB73B5d3e242bA5456b782919AFc85')
    
    // The blockchain address that receives the asset
    ->setRecipient('0xe242bA5456b782919AFc85687422eEA2cB73B5d3')
    
    // The type of the asset to transfer. Set <code>0</code> for fungible tokens (ERC-20 or equivalent),...
    ->setContractType(0)
    
    // (optional) (Only if the asset is a fungible token, NFT, or Multi Token) The address of the token to transfer...
    ->setTokenAddress('0x782919AFc85eEA2cB736874225456bB5d3e242bA')
    
    // (optional) (Only if the asset is a fungible token, Multi Token, or native blockchain currency) The amount of...
    ->setAmount('100000')
    
    // (optional) (Only if the asset is a Multi Token or NFT) The ID of the token to transfer. Do not use if the as...
    ->setTokenId('100000')
    
    // The private key of the blockchain address that owns the gas pump address ("master address")
    ->setFromPrivateKey('0x05e150c73f1920ec14caa1e0b6aa09940899678051a78542840c2668ce5080c2')
    
    // (optional) The nonce to be set to the transfer transaction; if not present, the last known nonce will be used
    ->setNonce(1)
    
    // (optional) \Tatum\Model\CustomFee
    ->setFee(null);

try {

    // 🐛 Enable debugging on the MainNet
    $sdk->mainnet()->config()->setDebug(true);

    /**
     * POST /v3/blockchain/sc/custodial/transfer
     * 
     * @var \Tatum\Model\TransactionSigned $response
     */
    $response = $sdk->mainnet()
        ->api()
        ->gasPump()
        ->transferCustodialWallet($arg_transfer_custodial_wallet);

    var_dump($response);

} catch (\Tatum\Sdk\ApiException $apiExc) {
    echo sprintf(
        "API Exception when calling api()->gasPump()->transferCustodialWallet(): %s\n", 
        var_export($apiExc->getResponseObject(), true)
    );
} catch (\Exception $exc) {
    echo sprintf(
        "Exception when calling api()->gasPump()->transferCustodialWallet(): %s\n", 
        $exc->getMessage()
    );
}