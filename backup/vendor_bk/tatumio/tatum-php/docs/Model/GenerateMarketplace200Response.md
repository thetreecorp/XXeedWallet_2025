---
title: GenerateMarketplace200Response
parent: Model
layout: page
---

# GenerateMarketplace200Response

## Model getters

Method name | Return type | Description | Notes
------------ | ------------- | ------------- | -------------
**getTxId()** | **string** | The hash (ID) of the transaction <br>Example: `c83f8818db43d9ba4accfe454aa44fc33123d47a4f89d47b314d6748eb0e9bc9` |
**getContractAddress()** | **string** | The address of deployed marketplace contract <br>Example: `9qhKAgVRebMnjVM4AHdHcseYQG47Mns3U8e7dRz24kg5` |
**getFeeAccount()** | **string** | The blockchain address of the fee account <br>Example: `B2va8BWefHKhKnejxiKxLxWYbpzwJWPsNGzEPCiYHQDH` |
**getTreasuryAccount()** | **string** | The blockchain address of the treasury account <br>Example: `9MLntRkghAgC7ZR1RQouE9EkXwjTfZxbi9nBziofPTjM` |
**getSignatureId()** | **string** | The internal Tatum ID of the prepared transaction for Key Management Sysytem (KMS) to sign<br/>This is different from the <code>signatureId</code> parameter that you provided in the request body. The <code>signatureId</code> parameter in the request body specifies the signature ID associated with the private key in KMS. <br>Example: `1f7f7c0c-3906-4aa1-9dfe-4b67c43918f6` |

