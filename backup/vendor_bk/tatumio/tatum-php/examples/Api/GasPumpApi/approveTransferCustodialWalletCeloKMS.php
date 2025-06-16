<?php
/**
 * Copyright (c) 2022-2023 tatum.io
 * 
 * @link    https://tatumio.github.io/tatum-php/Api/GasPumpApi/#approvetransfercustodialwalletcelokms
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

$arg_approve_transfer_custodial_wallet_celo_kms = (new \Tatum\Model\ApproveTransferCustodialWalletCeloKMS())
    
    // The blockchain to work with
    ->setChain('CELO')
    
    // The gas pump address that holds the asset
    ->setCustodialAddress('0x687422eEA2cB73B5d3e242bA5456b782919AFc85')
    
    // The blockchain address to allow the transfer of the asset from the gas pump address
    ->setSpender('0xe242bA5456b782919AFc85687422eEA2cB73B5d3')
    
    // The type of the asset to transfer. Set <code>0</code> for fungible tokens (ERC-20 or equivalent),...
    ->setContractType(0)
    
    // The address of the asset to transfer
    ->setTokenAddress('0x782919AFc85eEA2cB736874225456bB5d3e242bA')
    
    // (optional) (Only if the asset is a fungible token or Multi Token) The amount of the asset to transfer. Do no...
    ->setAmount('100000')
    
    // (optional) (Only if the asset is a Multi Token or NFT) The ID of the token to transfer. Do not use if the as...
    ->setTokenId('100000')
    
    // The KMS identifier of the private key of the blockchain address that owns the gas pump address ke...
    ->setSignatureId('26d3883e-4e17-48b3-a0ee-09a3e484ac83')
    
    // (optional) (Only if the signature ID is mnemonic-based) The index of the "master address"
    ->setIndex(null)
    
    // (optional) The currency in which the gas fee will be paid
    ->setFeeCurrency('null')
    
    // (optional) The nonce to be set to the transfer transaction; if not present, the last known nonce will be used
    ->setNonce(1)
    
    // (optional) \Tatum\Model\CustomFee
    ->setFee(null);

try {

    // 🐛 Enable debugging on the MainNet
    $sdk->mainnet()->config()->setDebug(true);

    /**
     * POST /v3/blockchain/sc/custodial/approve
     * 
     * @var \Tatum\Model\TransactionSigned $response
     */
    $response = $sdk->mainnet()
        ->api()
        ->gasPump()
        ->approveTransferCustodialWalletCeloKMS($arg_approve_transfer_custodial_wallet_celo_kms);

    var_dump($response);

} catch (\Tatum\Sdk\ApiException $apiExc) {
    echo sprintf(
        "API Exception when calling api()->gasPump()->approveTransferCustodialWalletCeloKMS(): %s\n", 
        var_export($apiExc->getResponseObject(), true)
    );
} catch (\Exception $exc) {
    echo sprintf(
        "Exception when calling api()->gasPump()->approveTransferCustodialWalletCeloKMS(): %s\n", 
        $exc->getMessage()
    );
}