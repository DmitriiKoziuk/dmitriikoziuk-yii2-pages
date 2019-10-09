<?php declare(strict_types=1);

namespace DmitriiKoziuk\yii2Pages\interfaces;

use DmitriiKoziuk\yii2Pages\forms\PageCreateForm;
use DmitriiKoziuk\yii2Pages\forms\PageUpdateForm;

interface PageServiceInterface
{
    public function createPage(PageCreateForm $pageCreateForm): PageUpdateForm;

    public function updatePage(PageUpdateForm $pageUpdateForm): PageUpdateForm;

    public function deletePage(int $pageId): void;

    public function getPageById(int $pageId): PageUpdateForm;
}