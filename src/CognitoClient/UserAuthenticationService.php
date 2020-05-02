<?php

declare(strict_types=1);

namespace Incognito\CognitoClient;

use Aws\Exception\AwsException;
use Aws\Result;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient as CognitoClient;
use Incognito\Exception\ExceptionFactory;
use Incognito\Entity\Password;
use Incognito\Entity\User;
use Incognito\Entity\UserAttribute\UserAttribute;

class UserAuthenticationService
{
    /**
     * @var \Aws\CognitoIdentityProvider\CognitoIdentityProviderClient
     */
    private $cognitoClient;

    /**
     * @var \Incognito\CognitoClient\CognitoCredentials
     */
    private $cognitoCredentials;

    /**
     * Constructor.
     *
     * @param \Aws\CognitoIdentityProvider\CognitoIdentityProviderClient $cognitoClient
     * @param \Incognito\CognitoClient\CognitoCredentials                $cognitoCredentials
     */
    public function __construct(
        CognitoClient $cognitoClient,
        CognitoCredentials $cognitoCredentials
    ) {
        $this->cognitoClient      = $cognitoClient;
        $this->cognitoCredentials = $cognitoCredentials;
    }

    /**
     * Log a user in via username and password
     *
     * @param  string $username
     * @param  string $password
     * @return \Aws\Result|null
     * @throws \Exception
     */
    public function loginUser(string $username, string $password): ?Result
    {
        $result = null;

        try {
            $result = $this->cognitoClient->adminInitiateAuth([
                'AuthFlow'       => 'ADMIN_NO_SRP_AUTH',
                'ClientId'       => $this->cognitoCredentials->getClientId(),
                'UserPoolId'     => $this->cognitoCredentials->getUserPoolId(),
                'AuthParameters' => [
                    'SECRET_HASH' => $this->cognitoCredentials->getSecretHashForUsername(
                        $username
                    ),
                    'USERNAME'    => $username,
                    'PASSWORD'    => $password,
                ],
            ]);
        } catch (AwsException $e) {
            throw ExceptionFactory::make($e);
        }

        return $result;
    }

    /**
     * Refresh the current AWS Cognito session and return a new access token
     *
     * @param  string $username
     * @param  string $refreshToken
     * @return \Aws\Result|null
     * @throws \Exception
     */
    public function refreshToken(string $username, string $refreshToken): ?Result
    {
        return $this->cognitoClient->adminInitiateAuth(
            [
              'AuthFlow'       => 'REFRESH_TOKEN_AUTH',
              'ClientId'       => $this->cognitoCredentials->getClientId(),
              'UserPoolId'     => $this->cognitoCredentials->getUserPoolId(),
              'AuthParameters' => [
                  'REFRESH_TOKEN' => $refreshToken,
                'SECRET_HASH'   => $this->cognitoCredentials->getSecretHashForUsername(
                    $username
                ),
                  'USERNAME'      => $username,
              ],
             ]
        );
    }

    /**
     * Sign up a new user
     *
     * @param  \Incognito\Entity\User     $user
     * @param  \Incognito\Entity\Password $password
     * @return \Aws\Result|null
     * @throws \Exception
     */
    public function signUpUser(User $user, Password $password): ?Result
    {
        $result = null;

        try {
            $result = $this->cognitoClient->signUp(
                [
                'ClientId'   => $this->cognitoCredentials->getClientId(),
                'Password'   => $password->password(),
                'SecretHash' => $this->cognitoCredentials->getSecretHashForUsername(
                    $user->username()
                ),
                'UserAttributes' => array_map(
                    function (UserAttribute $attr) {
                        return [
                            'Name'  => $attr->name(),
                            'Value' => $attr->value(),
                        ];
                    },
                    $user->getAttributes()
                ),
                'Username' => $user->username(),
                ]
            );
        } catch (AwsException $e) {
            throw ExceptionFactory::make($e);
        }

        return $result;
    }

    /**
     * Confirm a sign up for a new User without the User going through an email
     * or SMS confirmation flow.
     *
     * @param  string $username
     * @return \Aws\Result|null
     * @throws \Exception
     */
    public function adminConfirmSignUp(string $username): ?Result
    {
        $result = null;

        try {
            $result = $this->cognitoClient->adminConfirmSignUp(
                [
                'UserPoolId' => $this->cognitoCredentials->getUserPoolId(),
                'Username'   => $username,
                ]
            );
        } catch (AwsException $e) {
            throw ExceptionFactory::make($e);
        }

        return $result;
    }

    /**
     * Change a User's password
     *
     * @param  string                     $accessToken
     * @param  \Incognito\Entity\Password $previousPassword
     * @param  \Incognito\Entity\Password $proposedPassword
     * @return \Aws\Result|null
     * @throws \Exception
     */
    public function changePassword(
        string $accessToken,
        Password $previousPassword,
        Password $proposedPassword
    ): ?Result {
        $result = null;

        try {
            $result = $this->cognitoClient->changePassword(
                [
                'AccessToken'      => $accessToken,
                'PreviousPassword' => $previousPassword->password(),
                'ProposedPassword' => $proposedPassword->password(),
                ]
            );
        } catch (AwsException $e) {
            throw ExceptionFactory::make($e);
        }

        return $result;
    }
}
