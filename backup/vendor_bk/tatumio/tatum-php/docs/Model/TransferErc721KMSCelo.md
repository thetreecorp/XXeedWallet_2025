---
title: TransferErc721KMSCelo
parent: Model
layout: page
---

# TransferErc721KMSCelo

## Model getters

Method name | Return type | Description | Notes
------------ | ------------- | ------------- | -------------
**getTo()** | **string** | Blockchain address to send ERC721 token to <br>Example: `0x687422eEA2cB73B5d3e242bA5456b782919AFc85` |
**getTokenId()** | **string** | ID of token. <br>Example: `100000` |
**getContractAddress()** | **string** | Address of ERC721 token <br>Example: `0x687422eEA2cB73B5d3e242bA5456b782919AFc85` |
**getIndex()** | **float** | If signatureId is mnemonic-based, this is the index to the specific address from that mnemonic. <br>Example: `null` | [optional]
**getSignatureId()** | **string** | Identifier of the private key associated in signing application. Private key, or signature Id must be present. <br>Example: `26d3883e-4e17-48b3-a0ee-09a3e484ac83` |
**getNonce()** | **float** | Nonce to be set to Celo transaction. If not present, last known nonce will be used. <br>Example: `1` | [optional]
**getFeeCurrency()** | **string** | Currency to pay for transaction gas <br>Example: `null` |

