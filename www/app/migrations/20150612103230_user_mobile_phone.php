<?php

use Phinx\Migration\AbstractMigration;

class UserMobilePhone extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */
    
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('user');
        $table->addColumn('mobile_phone', 'string', array('limit' => 25, 'after' => 'phone', 'null' => true))
            ->save();
    
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table('user');
        $table->removeColumn('mobile_phone')
            ->save();
    }
}