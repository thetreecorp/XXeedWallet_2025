<?php
/**
 * Copyright (c) 2022-2023 tatum.io
 * 
 * @link    https://tatumio.github.io/tatum-php/Api/BlockchainOperationsApi/#deploytrcoffchainpkaddress
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

$arg_deploy_trc_offchain_pk_address = (new \Tatum\Model\DeployTrcOffchainPKAddress())
    
    // The name of the token; used as an identifier within the Tatum platform and as a currency symbol o...
    ->setSymbol('MY_TOKEN')
    
    // The supply of the token
    ->setSupply('10000000')
    
    // The number of decimal places that the token has
    ->setDecimals(6)
    
    // The type of the token
    ->setType('TRC10')
    
    // The description of the token; used as a description within the Tatum platform and as a currency n...
    ->setDescription('My Public Token')
    
    // The blockchain address to be assigned to the virtual account as a deposit address
    ->setAddress('TVAEYCmc15awaDRAjUZ1kvcHwQQaoPw2CW')
    
    // The private key of the blockchain address from which the fee for deploying the smart contract wil...
    ->setPrivateKey('e75d702ce00987633f8009fbb1eabb5b187cb5b50fe9179a8d6cee6bab076b66')
    
    // The base pair for the virtual currency that represents the token; used to calculate the value of ...
    ->setBasePair('EUR')
    
    // (optional) The exchange rate for the base pair; one unit of the created virtual currency equals 1 unit of <c...
    ->setBaseRate(1)
    
    // (optional) (TRC-10 tokens only) The URL of the project that the token is created for<br/>Use this parameter ...
    ->setUrl('https://mytoken.com')
    
    // (optional) \Tatum\Model\CustomerRegistration
    ->setCustomer(null);

try {

    // 🐛 Enable debugging on the MainNet
    $sdk->mainnet()->config()->setDebug(true);

    /**
     * POST /v3/offchain/tron/trc/deploy
     * 
     * @var \Tatum\Model\DeployErc20OffchainMnemonicAddress200Response $response
     */
    $response = $sdk->mainnet()
        ->api()
        ->blockchainOperations()
        ->deployTrcOffchainPKAddress($arg_deploy_trc_offchain_pk_address);

    var_dump($response);

} catch (\Tatum\Sdk\ApiException $apiExc) {
    echo sprintf(
        "API Exception when calling api()->blockchainOperations()->deployTrcOffchainPKAddress(): %s\n", 
        var_export($apiExc->getResponseObject(), true)
    );
} catch (\Exception $exc) {
    echo sprintf(
        "Exception when calling api()->blockchainOperations()->deployTrcOffchainPKAddress(): %s\n", 
        $exc->getMessage()
    );
}