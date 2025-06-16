---
title: ApproveTransferCustodialWallet
parent: Model
layout: page
---

# ApproveTransferCustodialWallet

## Model getters

Method name | Return type | Description | Notes
------------ | ------------- | ------------- | -------------
**getChain()** | **string** | The blockchain to work with <br>Example: `ETH` |
**getCustodialAddress()** | **string** | The gas pump address that holds the asset <br>Example: `0x687422eEA2cB73B5d3e242bA5456b782919AFc85` |
**getSpender()** | **string** | The blockchain address to allow the transfer of the asset from the gas pump address <br>Example: `0xe242bA5456b782919AFc85687422eEA2cB73B5d3` |
**getContractType()** | **float** | The type of the asset to transfer. Set <code>0</code> for fungible tokens (ERC-20 or equivalent), <code>1</code> for NFTs (ERC-721 or equivalent), or <code>2</code> for Multi Tokens (ERC-1155 or equivalent). <br>Example: `0` |
**getTokenAddress()** | **string** | The address of the asset to transfer <br>Example: `0x782919AFc85eEA2cB736874225456bB5d3e242bA` |
**getAmount()** | **string** | (Only if the asset is a fungible token or Multi Token) The amount of the asset to transfer. Do not use if the asset is an NFT. <br>Example: `100000` | [optional]
**getTokenId()** | **string** | (Only if the asset is a Multi Token or NFT) The ID of the token to transfer. Do not use if the asset is a fungible token. <br>Example: `100000` | [optional]
**getFromPrivateKey()** | **string** | The private key of the blockchain address that owns the gas pump address ("master address") <br>Example: `0x05e150c73f1920ec14caa1e0b6aa09940899678051a78542840c2668ce5080c2` |
**getNonce()** | **float** | The nonce to be set to the transfer transaction; if not present, the last known nonce will be used <br>Example: `1` | [optional]
**getFee()** | [**\Tatum\Model\CustomFee**](../CustomFee) |  <br>Example: `null` | [optional]

