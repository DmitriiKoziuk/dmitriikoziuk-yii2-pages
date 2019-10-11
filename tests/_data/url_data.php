<?php declare(strict_types=1);

namespace DmitriiKoziuk\yii2Pages\tests\_data;

use DmitriiKoziuk\yii2Pages\PagesModule;

return [
    [
        'id' => 1,
        'url' => '/Contact-page',
        'redirect_to_url' => null,
        'module_name' => PagesModule::ID,
        'controller_name' => PagesModule::FRONTEND_CONTROLLER_NAME,
        'action_name' => PagesModule::FRONTEND_CONTROLLER_ACTION,
        'entity_id' => '1',
        'created_at' => '1392559490',
        'updated_at' => '1392559490',
    ],
    [
        'id' => 1,
        'url' => '/Not-active-page',
        'redirect_to_url' => null,
        'module_name' => PagesModule::ID,
        'controller_name' => PagesModule::FRONTEND_CONTROLLER_NAME,
        'action_name' => PagesModule::FRONTEND_CONTROLLER_ACTION,
        'entity_id' => '2',
        'created_at' => '1392559490',
        'updated_at' => '1392559490',
    ],
];