<?php

use Phinx\Seed\AbstractSeed;

class OauthScopesSeeder extends AbstractSeed
{
    public function run()
    {
        $data = array(
            array(
                'id'    => 'getaccount',
                'description' => 'Get account details',
            ),
            array(
                'id'    => 'updateaccount',
                'description' => 'Update account details',
            ),
        );

        $oauth_clients = $this->table('oauth_scopes');
        $oauth_clients->insert($data)
              ->save();
    }
}
