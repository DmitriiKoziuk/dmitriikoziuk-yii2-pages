<?php declare(strict_types=1);

namespace DmitriiKoziuk\yii2Pages\tests\_data;

use DmitriiKoziuk\yii2Pages\forms\PageCreateForm;

return [
    [
        'id' => 1,
        'name' => 'Contact page',
        'is_active' => PageCreateForm::ACTIVE,
        'meta_title' => 'Contact page',
        'meta_description' => 'Contact page',
        'content' => 'You are on contact page.',
        'created_at' => '1392559490',
        'updated_at' => '1392559490',
    ],
    [
        'id' => 2,
        'name' => 'Not active page',
        'is_active' => PageCreateForm::NOT_ACTIVE,
        'meta_title' => 'Not active page title',
        'meta_description' => 'Not active page description',
        'content' => 'Not active page content.',
        'created_at' => '1392559490',
        'updated_at' => '1392559490',
    ],
];