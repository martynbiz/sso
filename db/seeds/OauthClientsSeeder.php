<?php

use Phinx\Seed\AbstractSeed;

class OauthClientsSeeder extends AbstractSeed
{
    public function run()
    {
        $data = array(
            array(
                'id'    => 'japantravel',
                'secret' => 'qwertyuiop1234567890',
                'name' => 'JapanTravel',
            ),
        );

        $oauth_clients = $this->table('oauth_clients');
        $oauth_clients->insert($data)
              ->save();
    }
}
