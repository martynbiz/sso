<?php

use Phinx\Migration\AbstractMigration;

class CreateRecoveryTokensTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('recovery_tokens', array(
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ));

        $table->addColumn('selector', 'string', array('limit' => 16 ));
        $table->addColumn('token', 'string', array('limit' => 64 ));
        $table->addColumn('user_id', 'integer');

        $table->addForeignKey('user_id', 'users', 'id', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'));
        $table->addIndex('selector', array('unique' => true));
        $table->addIndex('user_id', array('unique' => true));

        $table->addColumn('expire', 'datetime' );

        // timestamps
        $table->addColumn('created_at', 'datetime', array('null' => true));
        $table->addColumn('updated_at', 'datetime', array('null' => true));
        $table->addColumn('deleted_at', 'datetime', array('null' => true));

        $table->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('recovery_tokens');
    }
}
