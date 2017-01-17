<?php

use Phinx\Migration\AbstractMigration;

class CreateOauthRefreshTokensTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table( 'oauth_refresh_tokens', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci',
            'id' => false,
            'primary_key' => 'refresh_token', // string
        ) );

        $table->addColumn('refresh_token', 'string');
        $table->addColumn('expire_time', 'integer');
        $table->addColumn('access_token', 'string');
        $table->addForeignKey('access_token', 'oauth_access_tokens', 'access_token', array('delete'=> 'CASCADE'));

        // $table->string('refresh_token')->primary();
        // $table->integer('expire_time');
        // $table->string('access_token');
        // $table->foreign('access_token')->references('access_token')->on('oauth_access_tokens')->onDelete('cascade');

        // // timestamps
        // $table->addColumn( 'dt_create', 'datetime', array( 'null' => true ) );
        // $table->addColumn( 'dt_update', 'datetime', array( 'null' => true ) );
        // $table->addColumn( 'dt_delete', 'datetime', array( 'null' => true ) );

        // indexes
        $table->addIndex( array( 'refresh_token' ), array( 'unique' => true ) );

        $table->create();
    }
}
