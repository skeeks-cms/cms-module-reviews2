<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 03.07.2015
 */
use yii\db\Schema;
use yii\db\Migration;

class m150703_162718_create_table__reviews2_message extends Migration
{
    public function up()
    {
        $tableExist = $this->db->getTableSchema("{{%reviews2_message}}", true);
        if ($tableExist)
        {
            return true;
        }

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable("{{%reviews2_message}}", [
            'id'                    => Schema::TYPE_PK,

            'created_by'            => Schema::TYPE_INTEGER . ' NULL',
            'updated_by'            => Schema::TYPE_INTEGER . ' NULL',

            'created_at'            => Schema::TYPE_INTEGER . ' NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' NULL',

            'name'                  => Schema::TYPE_STRING . '(255) NOT NULL',
            'description'           => Schema::TYPE_TEXT . ' NULL',

            'code'                  => Schema::TYPE_STRING . '(32) NULL',

            'emails'                => Schema::TYPE_TEXT . ' NULL',
            'phones'                => Schema::TYPE_TEXT . ' NULL',
            'user_ids'              => Schema::TYPE_TEXT . ' NULL',

        ], $tableOptions);

        $this->execute("ALTER TABLE {{%reviews2_message}} ADD INDEX(updated_by);");
        $this->execute("ALTER TABLE {{%reviews2_message}} ADD INDEX(created_by);");

        $this->execute("ALTER TABLE {{%reviews2_message}} ADD INDEX(created_at);");
        $this->execute("ALTER TABLE {{%reviews2_message}} ADD INDEX(updated_at);");

        $this->execute("ALTER TABLE {{%reviews2_message}} ADD INDEX(name);");
        $this->execute("ALTER TABLE {{%reviews2_message}} ADD UNIQUE(code);");

        $this->execute("ALTER TABLE {{%reviews2_message}} COMMENT = 'Формы';");

        $this->addForeignKey(
            'reviews2_message_created_by', "{{%reviews2_message}}",
            'created_by', '{{%cms_user}}', 'id', 'SET NULL', 'SET NULL'
        );

        $this->addForeignKey(
            'reviews2_message_updated_by', "{{%reviews2_message}}",
            'updated_by', '{{%cms_user}}', 'id', 'SET NULL', 'SET NULL'
        );
    }

    public function down()
    {
        $this->dropForeignKey("reviews2_message_created_by", "{{%reviews2_message}}");
        $this->dropForeignKey("reviews2_message_updated_by", "{{%reviews2_message}}");

        $this->dropTable("{{%reviews2_message}}");
    }
}
