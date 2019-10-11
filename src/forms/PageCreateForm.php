<?php declare(strict_types=1);

namespace DmitriiKoziuk\yii2Pages\forms;

use yii\base\Model;

class PageCreateForm extends Model
{
    const ACTIVE = 1;

    const NOT_ACTIVE = 0;

    public $name;

    public $is_active;

    public $meta_title;

    public $meta_description;

    public $content;

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 150],
            [['name'], 'trim'],
            [['is_active'], 'integer'],
            [['is_active'], 'default', 'value' => self::NOT_ACTIVE],
            [['is_active'], function ($attribute) {
                if ($this->$attribute != self::ACTIVE && $this->$attribute != self::NOT_ACTIVE) {
                    $this->addError($attribute, "Page status code '{$this->$attribute}' not supported.");
                }
            }],
            [['meta_title', 'meta_description'], 'string', 'max' => 255],
            [['meta_title', 'meta_description'], 'trim'],
            [['content'], 'string'],
            [['meta_title', 'meta_description', 'content'], 'default', 'value' => null],
        ];
    }
}