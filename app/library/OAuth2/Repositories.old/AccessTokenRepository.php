<?php

namespace App\OAuth2\Repositories;

// use Illuminate\Database\Capsule\Manager as Capsule;
// use League\OAuth2\Server\Entity\AccessTokenEntity;
// use League\OAuth2\Server\Entity\ScopeEntity;
// use League\OAuth2\Server\Repositories\AbstractRepository;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        throw new \Exception('need to impement getNewToken');
    }

    /**
     * {@inheritdoc}
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        throw new \Exception('need to impement persistNewAccessToken');
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAccessToken($tokenId)
    {
        throw new \Exception('need to impement revokeAccessToken');
    }

    /**
     * {@inheritdoc}
     */
    public function isAccessTokenRevoked($tokenId)
    {
        throw new \Exception('need to impement isAccessTokenRevoked');
    }

    // /**
    //  * {@inheritdoc}
    //  */
    // public function get($token)
    // {
    //     $result = Capsule::table('oauth_access_tokens')
    //                         ->where('access_token', $token)
    //                         ->get();
    //
    //     if (count($result) === 1) {
    //         $token = (new AccessTokenEntity($this->server))
    //                     ->setId($result[0]->access_token)
    //                     ->setExpireTime($result[0]->expire_time);
    //
    //         return $token;
    //     }
    //
    //     return;
    // }
    //
    // /**
    //  * {@inheritdoc}
    //  */
    // public function getScopes(AccessTokenEntity $token)
    // {
    //     $result = Capsule::table('oauth_access_token_scopes')
    //                                 ->select(['oauth_scopes.id', 'oauth_scopes.description'])
    //                                 ->join('oauth_scopes', 'oauth_access_token_scopes.scope', '=', 'oauth_scopes.id')
    //                                 ->where('access_token', $token->getId())
    //                                 ->get();
    //
    //     $response = [];
    //
    //     if (count($result) > 0) {
    //         foreach ($result as $row) {
    //             $scope = (new ScopeEntity($this->server))->hydrate([
    //                 'id'            =>  $row['id'],
    //                 'description'   =>  $row['description'],
    //             ]);
    //             $response[] = $scope;
    //         }
    //     }
    //
    //     return $response;
    // }
    //
    // /**
    //  * {@inheritdoc}
    //  */
    // public function create($token, $expireTime, $sessionId)
    // {
    //     Capsule::table('oauth_access_tokens')
    //                 ->insert([
    //                     'access_token'     =>  $token,
    //                     'session_id'    =>  $sessionId,
    //                     'expire_time'   =>  $expireTime,
    //                 ]);
    // }
    //
    // /**
    //  * {@inheritdoc}
    //  */
    // public function associateScope(AccessTokenEntity $token, ScopeEntity $scope)
    // {
    //     Capsule::table('oauth_access_token_scopes')
    //                 ->insert([
    //                     'access_token'  =>  $token->getId(),
    //                     'scope' =>  $scope->getId(),
    //                 ]);
    // }
    //
    // /**
    //  * {@inheritdoc}
    //  */
    // public function delete(AccessTokenEntity $token)
    // {
    //     Capsule::table('oauth_access_tokens')
    //                 ->where('access_token', $token->getId())
    //                 ->delete();
    // }
}
