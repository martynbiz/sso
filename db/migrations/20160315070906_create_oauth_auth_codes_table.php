<?php

use Phinx\Migration\AbstractMigration;

class CreateOauthAuthCodesTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table( 'oauth_auth_codes', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci',
            'id' => false,
            'primary_key' => 'auth_code', // string
        ) );

        $table->addColumn('auth_code', 'string');
        $table->addColumn('session_id', 'integer');
        $table->addColumn('expire_time', 'integer');
        $table->addColumn('client_redirect_uri', 'string');
        $table->addForeignKey('session_id', 'oauth_sessions', 'id', array('delete'=> 'CASCADE'));

        // $table->string('auth_code')->primary();
        // $table->integer('session_id')->unsigned();
        // $table->integer('expire_time');
        // $table->string('client_redirect_uri');
        // $table->foreign('session_id')->references('id')->on('oauth_sessions')->onDelete('cascade');

        // // timestamps
        // $table->addColumn( 'dt_create', 'datetime', array( 'null' => true ) );
        // $table->addColumn( 'dt_update', 'datetime', array( 'null' => true ) );
        // $table->addColumn( 'dt_delete', 'datetime', array( 'null' => true ) );

        // indexes
        $table->addIndex( array( 'auth_code' ), array( 'unique' => true ) );

        $table->create();
    }
}
