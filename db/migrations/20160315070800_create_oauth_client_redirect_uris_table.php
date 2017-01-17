<?php

use Phinx\Migration\AbstractMigration;

class CreateOauthClientRedirectUrisTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table( 'oauth_client_redirect_uris', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ) );

        $table->addColumn('client_id', 'string'); // was a string
        $table->addColumn('redirect_uri', 'string');
        // $table->addForeignKey('client_id', 'oauth_clients', 'id', array('delete'=> 'CASCADE'));

        // $table->increments('id');
        // $table->string('client_id');
        // $table->string('redirect_uri');

        // // timestamps
        // $table->addColumn( 'dt_create', 'datetime', array( 'null' => true ) );
        // $table->addColumn( 'dt_update', 'datetime', array( 'null' => true ) );
        // $table->addColumn( 'dt_delete', 'datetime', array( 'null' => true ) );

        $table->create();
    }
}
