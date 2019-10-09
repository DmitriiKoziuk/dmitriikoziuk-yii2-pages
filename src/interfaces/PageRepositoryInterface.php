<?php declare(strict_types=1);

namespace DmitriiKoziuk\yii2Pages\interfaces;

use DmitriiKoziuk\yii2Base\interfaces\ActiveRecordRepositoryInterface;
use DmitriiKoziuk\yii2Pages\entities\PageEntity;

interface PageRepositoryInterface extends ActiveRecordRepositoryInterface
{
    public function getPageById(int $pageId): ?PageEntity;
}