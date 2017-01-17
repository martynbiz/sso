<?php

use Phinx\Migration\AbstractMigration;

class CreateOauthClientsTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table( 'oauth_clients', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci',
            'id' => false,
            'primary_key' => 'id', // string
        ) );

        $table->addColumn('id', 'string');
        $table->addColumn('secret', 'string');
        $table->addColumn('name', 'string');
        $table->addColumn('redirect_uri', 'string');
        $table->addColumn('is_confidential', 'boolean');

        // $table->string('id');
        // $table->string('secret');
        // $table->string('name');
        // $table->primary('id');

        // // timestamps
        // $table->addColumn( 'dt_create', 'datetime', array( 'null' => true ) );
        // $table->addColumn( 'dt_update', 'datetime', array( 'null' => true ) );
        // $table->addColumn( 'dt_delete', 'datetime', array( 'null' => true ) );

        // indexes
        $table->addIndex( array( 'id' ), array( 'unique' => true ) );

        $table->create();
    }
}
