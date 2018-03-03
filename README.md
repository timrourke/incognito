# Incognito

A suite of tools for working with AWS Cognito

[![CircleCI](https://circleci.com/gh/timrourke/incognito.svg?style=svg)](https://circleci.com/gh/timrourke/incognito) [![Maintainability](https://api.codeclimate.com/v1/badges/7328dca6e43b609e61e1/maintainability)](https://codeclimate.com/github/timrourke/incognito/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/7328dca6e43b609e61e1/test_coverage)](https://codeclimate.com/github/timrourke/incognito/test_coverage)

## In alpha

Development of this library is still in progress. Contributions are welcome and
encouraged.

## Goals

[AWS Cognito](https://aws.amazon.com/cognito/) is a very robust (and complicated)
product that provides a user identity and authentication framework. However, its
documentation is a little challenging to digest, and there aren't a ton of great
examples in the wild for how to integrate this service into a PHP application.
The goal of this project is to provide a few framework-agnostic tools to make it
a little easier to work with AWS Cognito, and the JSON Web Tokens (JWTs) it issues.

This library seeks to provide:

- [ ] A PSR-7 middleware for authenticating HTTP requests bearing a JWT issued by AWS Cognito
- [ ] A keychain service for fetching and cacheing the public RSA keyset used for verifying the authenticity of a JWT issued by AWS Cognito
- [ ] Several HTTP services useful for initiating authentication and user management flows in an AWS Cognito User Pool
