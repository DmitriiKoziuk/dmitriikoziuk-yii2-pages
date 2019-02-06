<?php
namespace DmitriiKoziuk\yii2Pages\records;

use DmitriiKoziuk\yii2Pages\PagesModule;

use Yii;

/**
 * This is the model class for table "{{%dk_pages}}".
 *
 * @property int    $id
 * @property string $name
 * @property string $slug
 * @property string $url
 * @property int    $is_active
 * @property string $meta_title
 * @property string $meta_description
 */
class PageRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%dk_pages}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'slug', 'url', 'meta_title', 'meta_description'], 'required'],
            [['is_active'], 'integer'],
            [['name'], 'string', 'max' => 150],
            [['slug'], 'string', 'max' => 165],
            [['url'], 'string', 'max' => 200],
            [['meta_title', 'meta_description'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['slug'], 'unique'],
            [['url'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t(PagesModule::TRANSLATE, 'ID'),
            'name' => Yii::t(PagesModule::TRANSLATE, 'Name'),
            'slug' => Yii::t(PagesModule::TRANSLATE, 'Slug'),
            'url' => Yii::t(PagesModule::TRANSLATE, 'Url'),
            'is_active' => Yii::t(PagesModule::TRANSLATE, 'Is Active'),
            'meta_title' => Yii::t(PagesModule::TRANSLATE, 'Meta Title'),
            'meta_description' => Yii::t(PagesModule::TRANSLATE, 'Meta Description'),
        ];
    }
}
