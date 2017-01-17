<?php

namespace App\OAuth2\Repositories;

// use Illuminate\Database\Capsule\Manager as Capsule;
// use League\OAuth2\Server\Entity\ScopeEntity;
// use League\OAuth2\Server\Repositories\AbstractRepository;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

class ScopeRepository implements ScopeRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function getScopeEntityByIdentifier($identifier)
    {
        throw new \Exception('getScopeEntityByIdentifier');
    }

    /**
     * {@inheritDoc}
     */
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    )
    {
        throw new \Exception('getScopeEntityByIdentifier');
    }

    // /**
    //  * {@inheritdoc}
    //  */
    // public function get($scope, $grantType = null, $clientId = null)
    // {
    //     $result = Capsule::table('oauth_scopes')
    //                             ->where('id', $scope)
    //                             ->get();
    //
    //     if (count($result) === 0) {
    //         return;
    //     }
    //
    //     return (new ScopeEntity($this->server))->hydrate([
    //         'id'            =>  $result[0]['id'],
    //         'description'   =>  $result[0]['description'],
    //     ]);
    // }
}
