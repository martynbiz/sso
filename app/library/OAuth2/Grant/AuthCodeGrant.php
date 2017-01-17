<?php
/**
 * Custom class to allow us to override certain methods (e.g. redirect handling)
 * We want to be able to return to any *.japantravel.com URL
 * e.g. https://th.japantravel.com/login?returnTo=http...
 * @author      Martyn Bissett <martyn@metroworks.co.jp>
 */

namespace App\OAuth2\Grant;

use League\OAuth2\Server\Entities\ClientEntityInterface;
// use League\OAuth2\Server\Entities\ScopeEntityInterface;
// use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
// use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
// use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\RequestEvent;
// use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
// use League\OAuth2\Server\ResponseTypes\RedirectResponse;
// use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Auth code grant class
 */
class AuthCodeGrant extends \League\OAuth2\Server\Grant\AuthCodeGrant
{
    /**
     * {@inheritdoc}
     */
    public function validateAuthorizationRequest(ServerRequestInterface $request)
    {
        $clientId = $this->getQueryStringParameter(
            'client_id',
            $request,
            $this->getServerParameter('PHP_AUTH_USER', $request)
        );
        if (is_null($clientId)) {
            throw OAuthServerException::invalidRequest('client_id');
        }

        $client = $this->clientRepository->getClientEntity(
            $clientId,
            $this->getIdentifier(),
            null,
            false
        );

        if ($client instanceof ClientEntityInterface === false) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::CLIENT_AUTHENTICATION_FAILED, $request));
            throw OAuthServerException::invalidClient();
        }

        $redirectUri = $this->getQueryStringParameter('redirect_uri', $request);
        if ($redirectUri !== null) {
            if (
                is_string($client->getRedirectUri())
                && (strcmp($client->getRedirectUri(), $redirectUri) !== 0)
            ) {
                $this->getEmitter()->emit(new RequestEvent(RequestEvent::CLIENT_AUTHENTICATION_FAILED, $request));
                throw OAuthServerException::invalidClient();
            } elseif (
                is_array($client->getRedirectUri())
                && in_array($redirectUri, $client->getRedirectUri()) === false
            ) {
                $this->getEmitter()->emit(new RequestEvent(RequestEvent::CLIENT_AUTHENTICATION_FAILED, $request));
                throw OAuthServerException::invalidClient();
            }
        }

        $scopes = $this->validateScopes(
            $this->getQueryStringParameter('scope', $request),
            is_array($client->getRedirectUri())
                ? $client->getRedirectUri()[0]
                : $client->getRedirectUri()
        );

        $stateParameter = $this->getQueryStringParameter('state', $request);

        $authorizationRequest = new AuthorizationRequest();
        $authorizationRequest->setGrantTypeId($this->getIdentifier());
        $authorizationRequest->setClient($client);
        $authorizationRequest->setRedirectUri($redirectUri);
        $authorizationRequest->setState($stateParameter);
        $authorizationRequest->setScopes($scopes);

        if ($this->enableCodeExchangeProof === true) {
            $codeChallenge = $this->getQueryStringParameter('code_challenge', $request);
            if ($codeChallenge === null) {
                throw OAuthServerException::invalidRequest('code_challenge');
            }

            $codeChallengeMethod = $this->getQueryStringParameter('code_challenge_method', $request, 'plain');
            if (in_array($codeChallengeMethod, ['plain', 'S256']) === false) {
                throw OAuthServerException::invalidRequest(
                    'code_challenge_method',
                    'Code challenge method must be `plain` or `S256`'
                );
            }

            $authorizationRequest->setCodeChallenge($codeChallenge);
            $authorizationRequest->setCodeChallengeMethod($codeChallengeMethod);
        }

        return $authorizationRequest;
    }



    // /**
    //  * Complete the auth code grant
    //  *
    //  * @return array
    //  *
    //  * @throws
    //  */
    // public function completeFlow()
    // {
    //     // Get the required params
    //     $clientId = $this->server->getRequest()->request->get('client_id', $this->server->getRequest()->getUser());
    //     if (is_null($clientId)) {
    //         throw new Exception\InvalidRequestException('client_id');
    //     }
    //
    //     $clientSecret = $this->server->getRequest()->request->get('client_secret',
    //         $this->server->getRequest()->getPassword());
    //     if ($this->shouldRequireClientSecret() && is_null($clientSecret)) {
    //         throw new Exception\InvalidRequestException('client_secret');
    //     }
    //
    //     $redirectUri = $this->server->getRequest()->request->get('redirect_uri', null);
    //     if (is_null($redirectUri)) {
    //         throw new Exception\InvalidRequestException('redirect_uri');
    //     }
    //
    //     // Validate client ID and client secret
    //     $clientStorage = $this->server->getClientStorage();
    //     $client = $clientStorage->get(
    //         $clientId,
    //         $clientSecret,
    //         $redirectUri,
    //         $this->getIdentifier()
    //     );
    //
    //     if (($client instanceof ClientEntity) === false) {
    //         $this->server->getEventEmitter()->emit(new Event\ClientAuthenticationFailedEvent($this->server->getRequest()));
    //         throw new Exception\InvalidClientException();
    //     }
    //
    //     // Validate the auth code
    //     $authCode = $this->server->getRequest()->request->get('code', null);
    //
    //     if (is_null($authCode)) {
    //         throw new Exception\InvalidRequestException('code');
    //     }
    //
    //     $code = $this->server->getAuthCodeStorage()->get($authCode);
    //     if (($code instanceof AuthCodeEntity) === false) {
    //         throw new Exception\InvalidRequestException('code');
    //     }
    //
    //     // Ensure the auth code hasn't expired
    //     if ($code->isExpired() === true) {
    //         throw new Exception\InvalidRequestException('code');
    //     }
    //
    //     // This line here we'll change. It should compare the redirect by it's host
    //     // without the subdomain either (as in some apps, eg. jt, we'll have many languages etc)
    //     // Check redirect URI presented matches redirect URI originally used in authorize request
    //     $redirectUriFromRequest = $clientStorage->getRedirectUriDomain($redirectUri);
    //     $redirectUriFromCode = $clientStorage->getRedirectUriDomain($code->getRedirectUri());
    //     if ($redirectUriFromRequest !== $redirectUriFromCode) {
    //         throw new Exception\InvalidRequestException('redirect_uri');
    //     }
    //
    //     $session = $code->getSession();
    //     $session->associateClient($client);
    //
    //     $authCodeScopes = $code->getScopes();
    //
    //     // Generate the access token
    //     $accessToken = new AccessTokenEntity($this->server);
    //     $accessToken->setId(SecureKey::generate());
    //     $accessToken->setExpireTime($this->getAccessTokenTTL() + time());
    //
    //     foreach ($authCodeScopes as $authCodeScope) {
    //         $session->associateScope($authCodeScope);
    //     }
    //
    //     foreach ($session->getScopes() as $scope) {
    //         $accessToken->associateScope($scope);
    //     }
    //
    //     $this->server->getTokenType()->setSession($session);
    //     $this->server->getTokenType()->setParam('access_token', $accessToken->getId());
    //     $this->server->getTokenType()->setParam('expires_in', $this->getAccessTokenTTL());
    //
    //     // Associate a refresh token if set
    //     if ($this->server->hasGrantType('refresh_token')) {
    //         $refreshToken = new RefreshTokenEntity($this->server);
    //         $refreshToken->setId(SecureKey::generate());
    //         $refreshToken->setExpireTime($this->server->getGrantType('refresh_token')->getRefreshTokenTTL() + time());
    //         $this->server->getTokenType()->setParam('refresh_token', $refreshToken->getId());
    //     }
    //
    //     // Expire the auth code
    //     $code->expire();
    //
    //     // Save all the things
    //     $accessToken->setSession($session);
    //     $accessToken->save();
    //
    //     if (isset($refreshToken) && $this->server->hasGrantType('refresh_token')) {
    //         $refreshToken->setAccessToken($accessToken);
    //         $refreshToken->save();
    //     }
    //
    //     return $this->server->getTokenType()->generateResponse();
    // }
}
