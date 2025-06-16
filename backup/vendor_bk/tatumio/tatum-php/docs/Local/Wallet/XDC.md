---
title: XDC
parent: Local/Wallet
layout: page
---

# Local/Wallet/XDC

```php
// Set your API Keys 👇 here
$sdk = new \Tatum\Sdk();

// MainNet Local/Wallet/XDC
$sdk->mainnet()->local()->wallet()->xdc();

// TestNet Local/Wallet/XDC
$sdk->testnet()->local()->wallet()->xdc();
```

XDC HD Wallet

Method | Description
------------- | -------------
[**generateWallet()**](#generatewallet) | Generate wallet
[**generateAddressFromXpub()**](#generateaddressfromxpub) | Generate address from xPub and index
[**generateAddressFromPrivateKey()**](#generateaddressfromprivatekey) | Generate address from xPub and index
[**generatePrivateKey()**](#generateprivatekey) | Generate private key from mnemonic and index

# `generateWallet()`

## Example

[👉 View "**generateWallet.php**" ✨](https://github.com/tatumio/tatum-php/blob/master/examples/Local/Wallet/XDC/generateWallet.php)

## Type signature

```php
(new \Tatum\Sdk())->{mainnet/testnet}()->local()->wallet()->xdc()->generateWallet(
    [ string $mnemonic = null ]
): \Tatum\Model\Wallet
```

## Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
**$mnemonic** | `string` | 24-word mnemonic |  [optional] [default to null]

## Return type

Wallet Model ([**\Tatum\Model\Wallet**](../../../Model/Wallet))

## Description

Generate wallet

[Back to top](#top)


# `generateAddressFromXpub()`

## Example

[👉 View "**generateAddressFromXpub.php**" ✨](https://github.com/tatumio/tatum-php/blob/master/examples/Local/Wallet/XDC/generateAddressFromXpub.php)

## Type signature

```php
(new \Tatum\Sdk())->{mainnet/testnet}()->local()->wallet()->xdc()->generateAddressFromXpub(
    string $xpub,
    int $index
): \Tatum\Model\GeneratedAddressBtc
```

## Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
**$xpub** | `string` | Extended public key | 
**$index** | `int` | Derivation index | 

## Return type

Address Model ([**\Tatum\Model\GeneratedAddressBtc**](../../../Model/GeneratedAddressBtc))

## Description

Generate address from xPub and index

[Back to top](#top)


# `generateAddressFromPrivateKey()`

## Example

[👉 View "**generateAddressFromPrivateKey.php**" ✨](https://github.com/tatumio/tatum-php/blob/master/examples/Local/Wallet/XDC/generateAddressFromPrivateKey.php)

## Type signature

```php
(new \Tatum\Sdk())->{mainnet/testnet}()->local()->wallet()->xdc()->generateAddressFromPrivateKey(
    \Tatum\Model\PrivKey $privateKey
): \Tatum\Model\GeneratedAddressBtc
```

## Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
**$privateKey** | [**\Tatum\Model\PrivKey**](../../../Model/PrivKey) | Private Key | 

## Return type

Address Model ([**\Tatum\Model\GeneratedAddressBtc**](../../../Model/GeneratedAddressBtc))

## Description

Generate address from xPub and index

[Back to top](#top)


# `generatePrivateKey()`

## Example

[👉 View "**generatePrivateKey.php**" ✨](https://github.com/tatumio/tatum-php/blob/master/examples/Local/Wallet/XDC/generatePrivateKey.php)

## Type signature

```php
(new \Tatum\Sdk())->{mainnet/testnet}()->local()->wallet()->xdc()->generatePrivateKey(
    string $mnemonic,
    int $index
): \Tatum\Model\PrivKey
```

## Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
**$mnemonic** | `string` | 24-word mnemonic | 
**$index** | `int` | Derivation index | 

## Return type

Private Key ([**\Tatum\Model\PrivKey**](../../../Model/PrivKey))

## Description

Generate private key from mnemonic and index

[Back to top](#top)

