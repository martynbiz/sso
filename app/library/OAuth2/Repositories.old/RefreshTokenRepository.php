<?php

namespace App\OAuth2\Repositories;

// use Illuminate\Database\Capsule\Manager as Capsule;
// use League\OAuth2\Server\Entity\RefreshTokenEntity;
// use League\OAuth2\Server\Repositories\AbstractRepository;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getNewRefreshToken()
    {
        throw new \Exception('need to impement getNewRefreshToken');
    }

    /**
     * {@inheritdoc}
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        throw new \Exception('need to impement persistNewRefreshToken');
    }

    /**
     * {@inheritdoc}
     */
    public function revokeRefreshToken($tokenId)
    {
        throw new \Exception('need to impement revokeRefreshToken');
    }

    /**
     * {@inheritdoc}
     */
    public function isRefreshTokenRevoked($tokenId)
    {
        throw new \Exception('need to impement isRefreshTokenRevoked');
    }

    // /**
    //  * {@inheritdoc}
    //  */
    // public function get($token)
    // {
    //     $result = Capsule::table('oauth_refresh_tokens')
    //                         ->where('refresh_token', $token)
    //                         ->get();
    //
    //     if (count($result) === 1) {
    //         $token = (new RefreshTokenEntity($this->server))
    //                     ->setId($result[0]->refresh_token)
    //                     ->setExpireTime($result[0]->expire_time)
    //                     ->setAccessTokenId($result[0]->access_token);
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
    // public function create($token, $expireTime, $accessToken)
    // {
    //     Capsule::table('oauth_refresh_tokens')
    //                 ->insert([
    //                     'refresh_token'     =>  $token,
    //                     'access_token'    =>  $accessToken,
    //                     'expire_time'   =>  $expireTime,
    //                 ]);
    // }
    //
    // /**
    //  * {@inheritdoc}
    //  */
    // public function delete(RefreshTokenEntity $token)
    // {
    //     Capsule::table('oauth_refresh_tokens')
    //                         ->where('refresh_token', $token->getId())
    //                         ->delete();
    // }
}
