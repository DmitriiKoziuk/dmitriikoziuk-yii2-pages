<?php declare(strict_types=1);

namespace DmitriiKoziuk\yii2Pages\repositories;

use DmitriiKoziuk\yii2Base\repositories\AbstractActiveRecordRepository;
use DmitriiKoziuk\yii2Pages\interfaces\PageRepositoryInterface;
use DmitriiKoziuk\yii2Pages\entities\PageEntity;

class PageRepository extends AbstractActiveRecordRepository implements PageRepositoryInterface
{
    public function getPageById(int $pageId): ?PageEntity
    {
        /** @var PageEntity|null $e */
        $e = PageEntity::find()
            ->where(['id' => $pageId])
            ->one();
        return $e;
    }
}