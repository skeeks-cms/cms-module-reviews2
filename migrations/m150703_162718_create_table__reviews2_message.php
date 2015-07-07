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

            'element_id'            => Schema::TYPE_INTEGER . ' NOT NULL',
            'content_id'            => Schema::TYPE_INTEGER . ' NOT NULL',

            'dignity'               => Schema::TYPE_TEXT . ' NULL',
            'disadvantages'         => Schema::TYPE_TEXT . ' NULL',
            'comments'              => Schema::TYPE_TEXT . ' NULL',

            'rating'                => Schema::TYPE_INTEGER . ' NOT NULL',

            'status'                => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0', //статус, активна некативна, удалено

            'ip'                    => Schema::TYPE_STRING . '(32) NULL',
            'page_url'              => Schema::TYPE_STRING . '(500) NULL',

            'data_server'           => Schema::TYPE_TEXT . ' NULL',
            'data_session'          => Schema::TYPE_TEXT . ' NULL',
            'data_cookie'           => Schema::TYPE_TEXT . ' NULL',
            'data_request'          => Schema::TYPE_TEXT . ' NULL',
            'additional_data'       => Schema::TYPE_TEXT . ' NULL',

            'site_code'             => "CHAR(15) NULL",

            'user_name'                 => Schema::TYPE_STRING . '(255) NULL',
            'user_email'                => Schema::TYPE_STRING . '(255) NULL',
            'user_phone'                => Schema::TYPE_STRING . '(255) NULL',
            'user_city'                 => Schema::TYPE_STRING . '(255) NULL',

        ], $tableOptions);

        $this->execute("ALTER TABLE {{%reviews2_message}} ADD INDEX(updated_by);");
        $this->execute("ALTER TABLE {{%reviews2_message}} ADD INDEX(created_by);");

        $this->execute("ALTER TABLE {{%reviews2_message}} ADD INDEX(created_at);");
        $this->execute("ALTER TABLE {{%reviews2_message}} ADD INDEX(updated_at);");

        $this->execute("ALTER TABLE {{%reviews2_message}} ADD INDEX(status);");
        $this->execute("ALTER TABLE {{%reviews2_message}} ADD INDEX(rating);");

        $this->execute("ALTER TABLE {{%reviews2_message}} ADD INDEX(element_id);");
        $this->execute("ALTER TABLE {{%reviews2_message}} ADD INDEX(content_id);");

        $this->execute("ALTER TABLE {{%reviews2_message}} ADD INDEX(ip);");
        $this->execute("ALTER TABLE {{%reviews2_message}} ADD INDEX(page_url);");
        $this->execute("ALTER TABLE {{%reviews2_message}} ADD INDEX(site_code);");

        $this->execute("ALTER TABLE {{%reviews2_message}} ADD INDEX(user_name);");
        $this->execute("ALTER TABLE {{%reviews2_message}} ADD INDEX(user_phone);");
        $this->execute("ALTER TABLE {{%reviews2_message}} ADD INDEX(user_email);");
        $this->execute("ALTER TABLE {{%reviews2_message}} ADD INDEX(user_city);");

        $this->execute("ALTER TABLE {{%reviews2_message}} COMMENT = 'Отзывы';");

        $this->addForeignKey(
            'reviews2_message_created_by', "{{%reviews2_message}}",
            'created_by', '{{%cms_user}}', 'id', 'SET NULL', 'SET NULL'
        );

        $this->addForeignKey(
            'reviews2_message_updated_by', "{{%reviews2_message}}",
            'updated_by', '{{%cms_user}}', 'id', 'SET NULL', 'SET NULL'
        );


        $this->addForeignKey(
            'reviews2_message_site_code_fk', "{{%reviews2_message}}",
            'site_code', '{{%cms_site}}', 'code', 'SET NULL', 'SET NULL'
        );

        $this->addForeignKey(
            'reviews2_message_element_id', "{{%reviews2_message}}",
            'element_id', '{{%cms_content_element}}', 'id', 'CASCADE', 'CASCADE'
        );

        $this->addForeignKey(
            'reviews2_message_content_id', "{{%reviews2_message}}",
            'content_id', '{{%cms_content}}', 'id', 'RESTRICT', 'RESTRICT'
        );
    }

    public function down()
    {
        $this->dropForeignKey("reviews2_message_created_by", "{{%reviews2_message}}");
        $this->dropForeignKey("reviews2_message_updated_by", "{{%reviews2_message}}");
        $this->dropForeignKey("reviews2_message_site_code_fk", "{{%reviews2_message}}");
        $this->dropForeignKey("reviews2_message_element_id", "{{%reviews2_message}}");
        $this->dropForeignKey("reviews2_message_content_id", "{{%reviews2_message}}");

        $this->dropTable("{{%reviews2_message}}");
    }
}
