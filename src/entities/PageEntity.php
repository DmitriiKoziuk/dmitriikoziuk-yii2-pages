<?php declare(strict_types=1);

namespace DmitriiKoziuk\yii2Pages\entities;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%dk_pages}}".
 *
 * @property int    $id
 * @property string $name
 * @property int    $is_active
 * @property string $meta_title
 * @property string $meta_description
 * @property string $content
 * @property int    $created_at
 * @property int    $updated_at
 */
class PageEntity extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%dk_pages}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_active'], 'integer'],
            [['content'], 'string'],
            [['name'], 'string', 'max' => 150],
            [['meta_title', 'meta_description'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'is_active' => 'Is Active',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'content' => 'Content',
        ];
    }
}
