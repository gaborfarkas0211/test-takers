<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%result}}`.
 */
class m210215_160703_create_result_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%result}}', [
            'id' => $this->primaryKey(),
            'testTaker' => $this->string()->notNull(),
            'correctAnswers' => $this->integer()->defaultValue(0),
            'incorrectAnswers' => $this->integer()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%result}}');
    }
}
