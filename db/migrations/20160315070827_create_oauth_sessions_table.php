<?php

use Phinx\Migration\AbstractMigration;

class CreateOauthSessionsTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table( 'oauth_sessions', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ) );

        $table->addColumn('owner_type', 'string');
        $table->addColumn('owner_id', 'integer'); // was a string
        $table->addColumn('client_id', 'string');
        $table->addColumn('client_redirect_uri', 'string');
        $table->addForeignKey('client_id', 'oauth_clients', 'id', array('delete'=> 'CASCADE'));
        // TODO should we have a foreign key on owner_id???

        // $table->increments('id')->unsigned();
        // $table->string('owner_type');
        // $table->string('owner_id');
        // $table->string('client_id');
        // $table->string('client_redirect_uri')->nullable();
        // $table->foreign('client_id')->references('id')->on('oauth_clients')->onDelete('cascade');

        // // timestamps
        // $table->addColumn( 'dt_create', 'datetime', array( 'null' => true ) );
        // $table->addColumn( 'dt_update', 'datetime', array( 'null' => true ) );
        // $table->addColumn( 'dt_delete', 'datetime', array( 'null' => true ) );

        $table->create();
    }
}
