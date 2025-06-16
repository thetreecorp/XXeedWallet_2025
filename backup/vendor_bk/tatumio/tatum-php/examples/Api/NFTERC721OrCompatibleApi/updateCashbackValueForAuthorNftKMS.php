<?php
/**
 * Copyright (c) 2022-2023 tatum.io
 * 
 * @link    https://tatumio.github.io/tatum-php/Api/NFTERC721OrCompatibleApi/#updatecashbackvalueforauthornftkms
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

$arg_update_cashback_value_for_author_nft_kms = (new \Tatum\Model\UpdateCashbackValueForAuthorNftKMS())
    
    // The blockchain to work with
    ->setChain('ETH')
    
    // The ID of the NFT to update royalty information for
    ->setTokenId('123')
    
    // The blockchain address of the NFT to update royalty information for
    ->setContractAddress('0x687422eEA2cB73B5d3e242bA5456b782919AFc85')
    
    // The new value of the royalty cashback to be set for the author of the NFT; to disable the royalti...
    ->setCashbackValue('0.1')
    
    // The KMS identifier of the private key of the NFT author's address
    ->setSignatureId('26d3883e-4e17-48b3-a0ee-09a3e484ac83')
    
    // (optional) (Only if the signature ID is mnemonic-based) The index of the NFT author's address that was gener...
    ->setIndex(null)
    
    // (optional) The nonce to be set to the transaction; if not present, the last known nonce will be used
    ->setNonce(null)
    
    // (optional) \Tatum\Model\CustomFee
    ->setFee(null);

// Type of Ethereum testnet. Defaults to Sepolia. Valid only for ETH invocations for testnet API Key. For mainnet API Key, this value is ignored.
$arg_x_testnet_type = 'ethereum-sepolia';

try {

    // 🐛 Enable debugging on the MainNet
    $sdk->mainnet()->config()->setDebug(true);

    /**
     * PUT /v3/nft/royalty
     * 
     * @var \Tatum\Model\TransactionSigned $response
     */
    $response = $sdk->mainnet()
        ->api()
        ->nFTERC721OrCompatible()
        ->updateCashbackValueForAuthorNftKMS($arg_update_cashback_value_for_author_nft_kms, $arg_x_testnet_type);

    var_dump($response);

} catch (\Tatum\Sdk\ApiException $apiExc) {
    echo sprintf(
        "API Exception when calling api()->nFTERC721OrCompatible()->updateCashbackValueForAuthorNftKMS(): %s\n", 
        var_export($apiExc->getResponseObject(), true)
    );
} catch (\Exception $exc) {
    echo sprintf(
        "Exception when calling api()->nFTERC721OrCompatible()->updateCashbackValueForAuthorNftKMS(): %s\n", 
        $exc->getMessage()
    );
}