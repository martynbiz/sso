<?php

use Phinx\Seed\AbstractSeed;

class OauthClientsSeeder extends AbstractSeed
{
    public function run()
    {
        $data = array(
            array(
                'id' => 'oeco',
                'secret' => password_hash('abc123', PASSWORD_BCRYPT),
                'name' => 'O-eco',
                'redirect_uri' => 'http://o-eco.vagrant/login',
                'is_confidential' => 0,
            ),
        );

        $oauth_clients = $this->table('oauth_clients');
        $oauth_clients->insert($data)
              ->save();
    }
}
