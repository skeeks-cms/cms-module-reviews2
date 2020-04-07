<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 28.08.2015
 */

use yii\db\Migration;

class m200406_120601__alter_table__reviews2_message extends Migration
{
    public function safeUp()
    {
        $tableName = "reviews2_message";

        $this->addColumn($tableName, "cms_site_id", $this->integer());

        $result = \Yii::$app->db->createCommand(<<<SQL
            UPDATE 
                `reviews2_message` as spts 
                LEFT JOIN cms_site site on site.code = spts.site_code 
            SET 
                spts.`cms_site_id` = site.id
SQL
        )->execute();

        $this->dropForeignKey("reviews2_message_site_code_fk", $tableName);
        $this->dropColumn($tableName, "site_code");


        $this->addForeignKey(
            "{$tableName}__cms_site_id", $tableName,
            'cms_site_id', '{{%cms_site}}', 'id', 'SET NULL', 'SET NULL'
        );
    }

    public function safeDown()
    {
        echo "m200406_120601__alter_table__reviews2_message cannot be reverted.\n";
        return false;
    }
}