<?php

use Phinx\Migration\AbstractMigration;

class CreateMetaTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table( 'meta', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ));

        $table->addColumn('name', 'string');
        $table->addColumn('value', 'string');
        $table->addColumn('user_id', 'integer');

        // foreign keys
        $table->addForeignKey('user_id', 'users', 'id', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'));

        // timestamps
        $table->addColumn('created_at', 'datetime', array( 'null' => true ));
        $table->addColumn('updated_at', 'datetime', array( 'null' => true ));
        $table->addColumn('deleted_at', 'datetime', array( 'null' => true ));

        $table->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable( 'meta' );
    }
}
