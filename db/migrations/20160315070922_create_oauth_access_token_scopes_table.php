<?php

use Phinx\Migration\AbstractMigration;

class CreateOauthAccessTokenScopesTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table( 'oauth_access_token_scopes', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci',
        ) );

        $table->addColumn('access_token', 'string');
        $table->addColumn('scope', 'string');
        // $table->addColumn('client_redirect_uri', 'string');
        $table->addForeignKey('access_token', 'oauth_access_tokens', 'access_token', array('delete'=> 'CASCADE'));
        $table->addForeignKey('scope', 'oauth_scopes', 'id', array('delete'=> 'CASCADE'));

        // $table->increments('id')->unsigned();
        // $table->string('access_token');
        // $table->string('scope');
        // $table->foreign('access_token')->references('access_token')->on('oauth_access_tokens')->onDelete('cascade');
        // $table->foreign('scope')->references('id')->on('oauth_scopes')->onDelete('cascade');

        // // timestamps
        // $table->addColumn( 'dt_create', 'datetime', array( 'null' => true ) );
        // $table->addColumn( 'dt_update', 'datetime', array( 'null' => true ) );
        // $table->addColumn( 'dt_delete', 'datetime', array( 'null' => true ) );

        $table->create();
    }
}
