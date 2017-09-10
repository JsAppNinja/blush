<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class SmsNotifications extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $table = $this->table('user');
        $table->addColumn('sms_message', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'after' => 'email_general', 'default' => 1))
            ->addColumn('sms_diary', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'after' => 'sms_message', 'default' => 1))
            ->addColumn('sms_reminder', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'after' => 'sms_diary', 'default' => 1))
            ->addColumn('sms_purchase', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'after' => 'sms_reminder', 'default' => 1))
            ->addColumn('sms_general', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'after' => 'sms_purchase', 'default' => 1))
            ->save();

    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table('diary');
        $table->removeColumn('sms_message')
            ->removeColumn('sms_diary')
            ->removeColumn('sms_reminder')
            ->removeColumn('sms_purchase')
            ->removeColumn('sms_general')
            ->save();
    }
}
