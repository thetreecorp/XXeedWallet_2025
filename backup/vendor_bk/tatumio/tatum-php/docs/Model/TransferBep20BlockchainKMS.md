---
title: TransferBep20BlockchainKMS
parent: Model
layout: page
---

# TransferBep20BlockchainKMS

## Model getters

Method name | Return type | Description | Notes
------------ | ------------- | ------------- | -------------
**getTo()** | **string** | Blockchain address to send ERC20 token to <br>Example: `0x687422eEA2cB73B5d3e242bA5456b782919AFc85` |
**getAmount()** | **string** | Amount to be sent. <br>Example: `100000` |
**getContractAddress()** | **string** | Address of ERC20 token <br>Example: `0x687422eEA2cB73B5d3e242bA5456b782919AFc85` |
**getDigits()** | **float** | Number of decimal points that ERC20 token has. <br>Example: `18` |
**getIndex()** | **float** | If signatureId is mnemonic-based, this is the index to the specific address from that mnemonic. <br>Example: `null` | [optional]
**getSignatureId()** | **string** | Identifier of the private key associated in signing application. Private key, or signature Id must be present. <br>Example: `26d3883e-4e17-48b3-a0ee-09a3e484ac83` |
**getNonce()** | **float** | Nonce to be set to Kcs transaction. If not present, last known nonce will be used. <br>Example: `null` | [optional]
**getFee()** | [**\Tatum\Model\DeployErc20Fee**](../DeployErc20Fee) |  <br>Example: `null` | [optional]

