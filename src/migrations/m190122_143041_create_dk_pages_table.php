<?php
namespace DmitriiKoziuk\yii2Pages\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `dk_pages`.
 */
class m190122_143041_create_dk_pages_table extends Migration
{
    private $pagesTable = '{{%dk_pages}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->pagesTable, [
            'id' => $this->primaryKey(),
            'name' => $this->string(150)->notNull(),
            'slug' => $this->string(165)->notNull(),
            'url' => $this->string(200)->notNull(),
            'is_active' => $this->boolean()->notNull()->defaultValue(0),
            'meta_title' => $this->string(255)->notNull(),
            'meta_description' => $this->string(255)->notNull(),
        ]);
        $this->createIndex(
            'idx_dk_pages_name',
            $this->pagesTable,
            'name',
            true
        );
        $this->createIndex(
            'idx_dk_pages_slug',
            $this->pagesTable,
            'slug',
            true
        );
        $this->createIndex(
            'idx_dk_pages_url',
            $this->pagesTable,
            'url',
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->pagesTable);
    }
}
