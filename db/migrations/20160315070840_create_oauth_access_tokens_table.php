<?php

use Phinx\Migration\AbstractMigration;

class CreateOauthAccessTokensTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table( 'oauth_access_tokens', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci',
            'id' => false,
            'primary_key' => 'access_token', // string
        ) );

        $table->addColumn('access_token', 'string');
        $table->addColumn('session_id', 'integer');
        $table->addColumn('expire_time', 'string');
        $table->addForeignKey('session_id', 'oauth_sessions', 'id', array('delete'=> 'CASCADE'));

        // $table->string('access_token')->primary();
        // $table->integer('session_id')->unsigned();
        // $table->integer('expire_time');
        // $table->foreign('session_id')->references('id')->on('oauth_sessions')->onDelete('cascade');

        // // timestamps
        // $table->addColumn( 'dt_create', 'datetime', array( 'null' => true ) );
        // $table->addColumn( 'dt_update', 'datetime', array( 'null' => true ) );
        // $table->addColumn( 'dt_delete', 'datetime', array( 'null' => true ) );

        // indexes
        $table->addIndex( array( 'access_token' ), array( 'unique' => true ) );

        $table->create();
    }
}
