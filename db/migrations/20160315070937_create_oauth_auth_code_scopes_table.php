<?php

use Phinx\Migration\AbstractMigration;

class CreateOauthAuthCodeScopesTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table( 'oauth_auth_code_scopes', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci',
        ) );

        $table->addColumn('auth_code', 'string');
        $table->addColumn('scope', 'string');
        $table->addForeignKey('auth_code', 'oauth_auth_codes', 'auth_code', array('delete'=> 'CASCADE'));
        $table->addForeignKey('scope', 'oauth_scopes', 'id', array('delete'=> 'CASCADE'));

        // $table->increments('id');
        // $table->string('auth_code');
        // $table->string('scope');
        // $table->foreign('auth_code')->references('auth_code')->on('oauth_auth_codes')->onDelete('cascade');
        // $table->foreign('scope')->references('id')->on('oauth_scopes')->onDelete('cascade');

        // // timestamps
        // $table->addColumn( 'dt_create', 'datetime', array( 'null' => true ) );
        // $table->addColumn( 'dt_update', 'datetime', array( 'null' => true ) );
        // $table->addColumn( 'dt_delete', 'datetime', array( 'null' => true ) );

        $table->create();
    }
}
