<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace App\OAuth2\Repositories;

use Illuminate\Database\Capsule\Manager as Capsule;

use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

use App\OAuth2\Entities\ClientEntity;

class ClientRepository implements ClientRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getClientEntity($clientIdentifier, $grantType, $clientSecret = null, $mustValidateSecret = true)
    {
        $client = Capsule::table('oauth_clients')
            ->select('oauth_clients.*')
            ->where('oauth_clients.id', $clientIdentifier)
            ->first();

        // verify client secret
        if (
            $mustValidateSecret === true
            && $client->is_confidential === true
            && password_verify($clientSecret, $client->secret) === false
        ) {
            return;
        }

        $clientEntity = new ClientEntity();
        $clientEntity->setIdentifier($clientIdentifier);
        $clientEntity->setName($client->name);
        $clientEntity->setRedirectUri($client->redirect_uri);

        return $clientEntity;
    }
}
