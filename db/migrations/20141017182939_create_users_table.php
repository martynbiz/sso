<?php

use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table( 'users', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ));

        $table->addColumn('first_name', 'string', array( 'limit' => 64 ));
        $table->addColumn('last_name', 'string', array( 'limit' => 64 ));
        $table->addColumn( 'username', 'string', array( 'limit' => 32 ) );
        $table->addColumn( 'password', 'string', array( 'limit' => 128 ) );
        // $table->addColumn( 'salt', 'string', array( 'limit' => 128 ) );
        $table->addColumn( 'email', 'string', array( 'limit' => 255 ) );

        // timestamps
        $table->addColumn( 'created_at', 'datetime', array( 'null' => true ) );
        $table->addColumn( 'updated_at', 'datetime', array( 'null' => true ) );
        $table->addColumn( 'deleted_at', 'datetime', array( 'null' => true ) );

        // indexes
        $table->addIndex(array('username'), array('unique' => true));
        $table->addIndex(array('email'), array('unique' => true ));

        $table->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable( 'users' );
    }
}
