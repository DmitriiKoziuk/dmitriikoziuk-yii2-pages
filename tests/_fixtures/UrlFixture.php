<?php

namespace DmitriiKoziuk\yii2Pages\tests\_fixtures;

use DmitriiKoziuk\yii2UrlIndex\entities\UrlEntity;
use yii\test\ActiveFixture;

class UrlFixture extends ActiveFixture
{
    public $modelClass = UrlEntity::class;
}