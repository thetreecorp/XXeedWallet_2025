---
title: ChainBurnErc20KMS
parent: Model
layout: page
---

# ChainBurnErc20KMS

## Model getters

Method name | Return type | Description | Notes
------------ | ------------- | ------------- | -------------
**getChain()** | **string** | The blockchain to work with <br>Example: `ETH` |
**getAmount()** | **string** | Amount of tokens to be destroyed. <br>Example: `100000` |
**getContractAddress()** | **string** | Address of ERC20 token <br>Example: `0x687422eEA2cB73B5d3e242bA5456b782919AFc85` |
**getSignatureId()** | **string** | Identifier of the private key associated in signing application. Private key, or signature Id must be present. <br>Example: `26d3883e-4e17-48b3-a0ee-09a3e484ac83` |
**getFee()** | [**\Tatum\Model\CustomFee**](../CustomFee) |  <br>Example: `null` | [optional]
**getNonce()** | **float** | The nonce to be set to the transaction; if not present, the last known nonce will be used <br>Example: `null` | [optional]

