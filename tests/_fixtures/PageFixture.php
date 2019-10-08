<?php

namespace DmitriiKoziuk\yii2Pages\tests\_fixtures;

use DmitriiKoziuk\yii2Pages\entities\PageEntity;
use yii\test\ActiveFixture;

class PageFixture extends ActiveFixture
{
    public $modelClass = PageEntity::class;
}