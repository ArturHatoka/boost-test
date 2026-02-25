<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%client}}`.
 */
class m260225_094500_create_client_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%client}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'state' => $this->string(128)->notNull(),
            'status' => $this->string(16)->notNull()->defaultValue('Active'),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%client}}');
    }
}
