# JSON:API client

[![Build Status](https://img.shields.io/travis/iPublikuj/json-api-client.svg?style=flat-square)](https://travis-ci.org/iPublikuj/json-api-client)
[![Scrutinizer Code Coverage](https://img.shields.io/scrutinizer/coverage/g/iPublikuj/json-api-client.svg?style=flat-square)](https://scrutinizer-ci.com/g/iPublikuj/json-api-client/?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/iPublikuj/json-api-client.svg?style=flat-square)](https://scrutinizer-ci.com/g/iPublikuj/json-api-client/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/ipub/json-api-client.svg?style=flat-square)](https://packagist.org/packages/ipub/json-api-client)
[![Composer Downloads](https://img.shields.io/packagist/dt/ipub/json-api-client.svg?style=flat-square)](https://packagist.org/packages/ipub/json-api-client)
[![License](https://img.shields.io/packagist/l/ipub/json-api-client.svg?style=flat-square)](https://packagist.org/packages/ipub/json-api-client)

Extension for creating [json:api](http://jsonapi.org) client for [Nette Framework](http://nette.org/)

## Installation

The best way to install ipub/json-api-client is using  [Composer](http://getcomposer.org/):

```sh
$ composer require ipub/json-api-client
```

After that you have to register extension in config.neon.

```neon
extensions:
	jsonApiClient: IPub\JsonAPIClient\DI\JsonAPIClientExtension
```

## Documentation

Learn how to use smart confirmation dialogs in [documentation](https://github.com/iPublikuj/json-api-client/blob/master/docs/en/index.md).

***
Homepage [https://www.ipublikuj.eu](https://www.ipublikuj.eu) and repository [http://github.com/iPublikuj/json-api-client](http://github.com/iPublikuj/json-api-client).
