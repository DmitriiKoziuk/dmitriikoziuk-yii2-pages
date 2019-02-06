<?php
namespace DmitriiKoziuk\yii2Pages\repositories;

use DmitriiKoziuk\yii2Base\repositories\EntityRepository;
use DmitriiKoziuk\yii2Pages\records\PageRecord;

class PageRepository extends EntityRepository
{
    public function getById(int $id): ?PageRecord
    {
        /** @var PageRecord|null $pageRecord */
        $pageRecord = PageRecord::find()->where(['id' => $id])->one();
        return $pageRecord;
    }
}