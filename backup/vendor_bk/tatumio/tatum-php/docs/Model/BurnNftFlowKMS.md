---
title: BurnNftFlowKMS
parent: Model
layout: page
---

# BurnNftFlowKMS

## Model getters

Method name | Return type | Description | Notes
------------ | ------------- | ------------- | -------------
**getChain()** | **string** | The blockchain to work with <br>Example: `FLOW` |
**getTokenId()** | **string** | ID of token to be destroyed. <br>Example: `123` |
**getContractAddress()** | **string** | Address of NFT token <br>Example: `17a50dad-bcb1-4f3d-ae2c-ea2bfb04419f` |
**getAccount()** | **string** | Blockchain address of the sender account. <br>Example: `0xc1b45bc27b9c61c3` |
**getSignatureId()** | **string** | Identifier of the private key associated in signing application. Private key, or signature Id must be present. <br>Example: `26d3883e-4e17-48b3-a0ee-09a3e484ac83` |
**getIndex()** | **int** | Derivation index of sender address. <br>Example: `0` | [optional]

