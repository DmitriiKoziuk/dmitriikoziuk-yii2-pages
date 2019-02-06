<?php
namespace DmitriiKoziuk\yii2Pages\forms;

use Yii;
use yii\base\Model;
use DmitriiKoziuk\yii2Pages\PagesModule;

class PageInputForm extends Model
{
    public $name;
    public $slug;
    public $url;
    public $is_active = 0;
    public $meta_title;
    public $meta_description;
    public $content;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_active'], 'integer'],
            [['name'], 'string', 'max' => 150],
            [['slug'], 'string', 'max' => 165],
            [['url'], 'string', 'max' => 200],
            [['meta_title', 'meta_description'], 'string', 'max' => 255],
            [['content'], 'string'],
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
            'content' => Yii::t(PagesModule::TRANSLATE, 'Content'),
        ];
    }
}