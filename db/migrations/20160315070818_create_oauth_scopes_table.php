<?php

use Phinx\Migration\AbstractMigration;

class CreateOauthScopesTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table( 'oauth_scopes', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci',
            'id' => false,
            'primary_key' => 'id', // string
        ) );

        $table->addColumn('id', 'string');
        $table->addColumn('description', 'string');

        // $table->string('id');
        // $table->string('description');
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
