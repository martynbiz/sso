<?php

use Phinx\Seed\AbstractSeed;

class OauthClientRedirectUrisSeeder extends AbstractSeed
{
    public function run()
    {
        $data = array(
            array(
                'client_id'    => 'japantravel',
                'redirect_uri' => 'japantravel.com',
            ),
            array(
                'client_id'    => 'japantravel',
                'redirect_uri' => 'jt2.staging.metroworks.co.jp',
            ),
            array(
                'client_id'    => 'japantravel',
                'redirect_uri' => 'jt.martyndev',
            ),
        );

        $oauth_clients = $this->table('oauth_client_redirect_uris');
        $oauth_clients->insert($data)
              ->save();
    }
}
