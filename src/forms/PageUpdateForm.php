<?php declare(strict_types=1);

namespace DmitriiKoziuk\yii2Pages\forms;

class PageUpdateForm extends PageCreateForm
{
    public $id;

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [
            ['id'], 'required'
        ];
        $rules[] = [
            ['id'], 'integer'
        ];
        return $rules;
    }
}