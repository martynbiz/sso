<?php

use Phinx\Seed\AbstractSeed;

class OauthClientRedirectUrisSeeder extends AbstractSeed
{
    public function run()
    {
        $data = array(
            array(
                'client_id'    => 'oeco',
                'redirect_uri' => 'o-eco.vagrant',
            ),
        );

        $oauth_clients = $this->table('oauth_client_redirect_uris');
        $oauth_clients->insert($data)
              ->save();
    }
}
