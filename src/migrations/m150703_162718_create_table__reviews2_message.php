<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 03.07.2015
 */

use yii\db\Migration;
use yii\db\Schema;

class m150703_162718_create_table__reviews2_message extends Migration
{
    public function safeUp()
    {
        $tableExist = $this->db->getTableSchema("{{%reviews2_message}}", true);
        if ($tableExist) {
            return true;
        }

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable("{{%reviews2_message}}", [
            'id' => Schema::TYPE_PK,

            'created_by' => Schema::TYPE_INTEGER . ' NULL',
            'updated_by' => Schema::TYPE_INTEGER . ' NULL',

            'created_at' => Schema::TYPE_INTEGER . ' NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NULL',
            'published_at' => Schema::TYPE_INTEGER . ' NULL',

            'processed_by' => Schema::TYPE_INTEGER . ' NULL', //пользователь который принял заявку
            'processed_at' => Schema::TYPE_INTEGER . ' NULL', //пользователь который принял заявку

            'element_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'content_id' => Schema::TYPE_INTEGER . ' NULL',

            'dignity' => Schema::TYPE_TEXT . ' NULL',
            'disadvantages' => Schema::TYPE_TEXT . ' NULL',
            'comments' => Schema::TYPE_TEXT . ' NULL',

            'rating' => Schema::TYPE_INTEGER . ' NOT NULL',

            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0', //статус, активна некативна, удалено

            'ip' => Schema::TYPE_STRING . '(32) NULL',
            'page_url' => Schema::TYPE_TEXT . ' NULL',

            'data_server' => Schema::TYPE_TEXT . ' NULL',
            'data_session' => Schema::TYPE_TEXT . ' NULL',
            'data_cookie' => Schema::TYPE_TEXT . ' NULL',
            'data_request' => Schema::TYPE_TEXT . ' NULL',

            'site_code' => "CHAR(15) NULL",

            'user_name' => Schema::TYPE_STRING . '(255) NULL',
            'user_email' => Schema::TYPE_STRING . '(255) NULL',
            'user_phone' => Schema::TYPE_STRING . '(255) NULL',
            'user_city' => Schema::TYPE_STRING . '(255) NULL',

        ], $tableOptions);

        $this->createIndex('reviews2_message__updated_by', '{{%reviews2_message}}', 'updated_by');
        $this->createIndex('reviews2_message__created_by', '{{%reviews2_message}}', 'created_by');
        $this->createIndex('reviews2_message__created_at', '{{%reviews2_message}}', 'created_at');
        $this->createIndex('reviews2_message__updated_at', '{{%reviews2_message}}', 'updated_at');
        $this->createIndex('reviews2_message__published_at', '{{%reviews2_message}}', 'published_at');
        $this->createIndex('reviews2_message__processed_at', '{{%reviews2_message}}', 'processed_at');
        $this->createIndex('reviews2_message__processed_by', '{{%reviews2_message}}', 'processed_by');
        $this->createIndex('reviews2_message__status', '{{%reviews2_message}}', 'status');
        $this->createIndex('reviews2_message__rating', '{{%reviews2_message}}', 'rating');
        $this->createIndex('reviews2_message__element_id', '{{%reviews2_message}}', 'element_id');
        $this->createIndex('reviews2_message__content_id', '{{%reviews2_message}}', 'content_id');
        $this->createIndex('reviews2_message__ip', '{{%reviews2_message}}', 'ip');
        $this->createIndex('reviews2_message__site_code', '{{%reviews2_message}}', 'site_code');
        $this->createIndex('reviews2_message__user_name', '{{%reviews2_message}}', 'user_name');
        $this->createIndex('reviews2_message__user_phone', '{{%reviews2_message}}', 'user_phone');
        $this->createIndex('reviews2_message__user_email', '{{%reviews2_message}}', 'user_email');
        $this->createIndex('reviews2_message__user_city', '{{%reviews2_message}}', 'user_city');

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
            'content_id', '{{%cms_content}}', 'id', 'SET NULL', 'SET NULL'
        );

        $this->addForeignKey(
            'reviews2_message_processed_by', "{{%reviews2_message}}",
            'processed_by', '{{%cms_user}}', 'id', 'SET NULL', 'SET NULL'
        );

    }

    public function down()
    {
        $this->dropForeignKey("reviews2_message_created_by", "{{%reviews2_message}}");
        $this->dropForeignKey("reviews2_message_updated_by", "{{%reviews2_message}}");
        $this->dropForeignKey("reviews2_message_site_code_fk", "{{%reviews2_message}}");
        $this->dropForeignKey("reviews2_message_element_id", "{{%reviews2_message}}");
        $this->dropForeignKey("reviews2_message_content_id", "{{%reviews2_message}}");
        $this->dropForeignKey("reviews2_message_processed_by", "{{%reviews2_message}}");

        $this->dropTable("{{%reviews2_message}}");
    }
}
