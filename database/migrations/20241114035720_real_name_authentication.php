<?php

use think\migration\Migrator;
use think\migration\db\Column;

class RealNameAuthentication extends Migrator
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
    public function change()
    {  
        $this->realNameAuth();

    }


    public function realNameAuth(): void
    {
        if (!$this->hasTable('real_name_auth')) {
            $table = $this->table('real_name_auth', [
                'id'          => false,
                'comment'     => '用户实名认证表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
            ->addColumn('user_id', 'integer', ['comment' => '用户id', 'default' => 0, 'signed' => false, 'null' => false])
            ->addColumn('realname', 'string', ['limit' => 20, 'default' => '', 'comment' => '用户实名', 'null' => false])
            ->addColumn('icard', 'string', ['limit' => 50, 'default' => '', 'comment' => '身份证号', 'null' => false])
            ->addColumn('icard_front', 'string', ['limit' => 255, 'default' => '', 'comment' => '身份证正面', 'null' => false])
            ->addColumn('icard_back', 'string', ['limit' => 255, 'default' => '', 'comment' => '身份证反面', 'null' => false])
            ->addColumn('status', 'enum', ['values' => '0,1,2', 'default' => '1', 'comment' => '状态:0=待审核,1=审核通过,2=拒绝', 'null' => false])
            ->addColumn('refuse_desc', 'string', ['limit' => 255, 'default' => '', 'comment' => '拒绝原因', 'null' => false])
            ->addTimestamps()
            ->addSoftDelete()
            ->addIndex(['icard'], [
                'unique' => true,
            ])
            ->create();
        }
    }

}
