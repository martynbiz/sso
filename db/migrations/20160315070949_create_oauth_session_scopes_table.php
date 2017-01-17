<?php

use Phinx\Migration\AbstractMigration;

class CreateOauthSessionScopesTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table( 'oauth_session_scopes', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci',
        ) );

        $table->addColumn('session_id', 'integer');
        $table->addColumn('scope', 'string');
        $table->addForeignKey('session_id', 'oauth_sessions', 'id', array('delete'=> 'CASCADE'));
        $table->addForeignKey('scope', 'oauth_scopes', 'id', array('delete'=> 'CASCADE'));

        // $table->increments('id')->unsigned();
        // $table->integer('session_id')->unsigned();
        // $table->string('scope');
        // $table->foreign('session_id')->references('id')->on('oauth_sessions')->onDelete('cascade');
        // $table->foreign('scope')->references('id')->on('oauth_scopes')->onDelete('cascade');

        // // timestamps
        // $table->addColumn( 'dt_create', 'datetime', array( 'null' => true ) );
        // $table->addColumn( 'dt_update', 'datetime', array( 'null' => true ) );
        // $table->addColumn( 'dt_delete', 'datetime', array( 'null' => true ) );

        $table->create();
    }
}
