<?php declare(strict_types=1);

namespace DmitriiKoziuk\yii2Pages\tests\_data;

use DmitriiKoziuk\yii2Pages\forms\PageCreateForm;

return [
    [
        'id' => 1,
        'name' => 'Contact page',
        'is_active' => PageCreateForm::NOT_ACTIVE,
        'meta_title' => 'Contact page',
        'meta_description' => 'Contact page',
        'content' => 'Hello',
        'created_at' => '1392559490',
        'updated_at' => '1392559490',
    ],
];