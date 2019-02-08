<?php
namespace DmitriiKoziuk\yii2Pages\services;

use yii\db\Connection;
use DmitriiKoziuk\yii2Base\helpers\UrlHelper;
use DmitriiKoziuk\yii2Base\helpers\FileHelper;
use DmitriiKoziuk\yii2Base\services\EntityActionService;
use DmitriiKoziuk\yii2CustomUrls\services\UrlIndexService;
use DmitriiKoziuk\yii2CustomUrls\forms\UrlCreateForm;
use DmitriiKoziuk\yii2CustomUrls\forms\UrlUpdateForm;
use DmitriiKoziuk\yii2CustomUrls\forms\UrlDeleteForm;
use DmitriiKoziuk\yii2Pages\PagesModule;
use DmitriiKoziuk\yii2Pages\entities\PageEntity;
use DmitriiKoziuk\yii2Pages\forms\PageInputForm;
use DmitriiKoziuk\yii2Pages\records\PageRecord;
use DmitriiKoziuk\yii2Pages\repositories\PageRepository;

final class PageService extends EntityActionService
{
    /**
     * @var string
     */
    private $_contentStoragePath;

    /**
     * @var PageRepository
     */
    private $_pageRepository;

    /**
     * @var UrlHelper
     */
    private $_urlHelper;

    /**
     * @var FileHelper
     */
    private $_fileHelper;

    /**
     * @var UrlIndexService
     */
    private $_urlIndexService;

    public function __construct(
        string $contentStoragePath,
        PageRepository $pageRepository,
        UrlHelper $urlHelper,
        FileHelper $fileHelper,
        UrlIndexService $urlIndexService,
        Connection $db
    ) {
        parent::__construct($db);
        $this->_contentStoragePath = $contentStoragePath;
        $this->_pageRepository = $pageRepository;
        $this->_urlHelper = $urlHelper;
        $this->_fileHelper = $fileHelper;
        $this->_urlIndexService = $urlIndexService;
    }

    public function createPage(PageInputForm $pageInputForm): PageEntity
    {
        $pageRecord = new PageRecord();
        $pageRecord = $this->_savePage($pageRecord, $pageInputForm);
        return new PageEntity($pageRecord, $pageInputForm->content);
    }

    public function updatePage(int $pageId, PageInputForm $pageInputForm): PageEntity
    {
        $pageRecord = $this->_getPageRecordById($pageId);
        $this->_savePage($pageRecord, $pageInputForm);
        return new PageEntity($pageRecord, $pageInputForm->content);
    }

    public function deletePage(int $id): void
    {
        $this->beginTransaction();
        try {
            $pageRecord = $this->_getPageRecordById($id);
            $pageRecord->delete();
            $this->_deletePageContent($pageRecord->id);
            $urlDeleteForm = new UrlDeleteForm(['url' => $pageRecord->url]);
            $this->_urlIndexService->deleteUrlFromIndex($urlDeleteForm);
            $this->commitTransaction();
        } catch (\Throwable $e) {
            $this->rollbackTransaction();
        }
    }

    public function getPageById(int $id): PageEntity
    {
        $pageRecord = $this->_getPageRecordById($id);
        $content = $this->_getPageContent($pageRecord->id);
        return new PageEntity($pageRecord, $content);
    }

    private function _getPageRecordById(int $pageId): PageRecord
    {
        $pageRecord = $this->_pageRepository->getById($pageId);
        if (empty($pageRecord)) {
            throw new \Exception('Page Record not found.');
        }
        return $pageRecord;
    }

    public function _savePage(PageRecord $pageRecord, PageInputForm $pageInputForm): PageRecord
    {
        $this->beginTransaction();
        try {
            $isNewRecord = $pageRecord->isNewRecord;
            $pageRecord->setAttributes($pageInputForm->getAttributes());
            $this->_defineSlug($pageRecord);
            $this->_defineUrl($pageRecord);
            $this->_defineMetaTitle($pageRecord);
            $this->_defineMetaDescription($pageRecord);
            $changedAttributes = $pageRecord->getDirtyAttributes();
            $this->_pageRepository->save($pageRecord);
            $this->_savePageContent($pageRecord->id, $pageInputForm->content);
            $this->_updatePageUrlInIndex($isNewRecord, $changedAttributes, $pageRecord);
            $this->commitTransaction();
            return $pageRecord;
        } catch (\Throwable $e) {
            $this->rollbackTransaction();
            throw $e;
        }
    }

    private function _defineSlug(PageRecord $pageRecord): void
    {
        if (empty($pageRecord->slug)) {
            $pageRecord->slug = $this->_urlHelper->slugFromString($pageRecord->name);
        }
    }

    private function _defineUrl(PageRecord $pageRecord): void
    {
        if (empty($pageRecord->url)) {
            $pageRecord->url = '/' . $pageRecord->slug;
        }
    }

    private function _defineMetaTitle(PageRecord $pageRecord): void
    {
        if (empty($pageRecord->meta_title)) {
            $pageRecord->meta_title = $pageRecord->name;
        }
    }

    private function _defineMetaDescription(PageRecord $pageRecord): void
    {
        if (empty($pageRecord->meta_description)) {
            $pageRecord->meta_description = $pageRecord->name;
        }
    }

    private function _updatePageUrlInIndex(
        bool $isPageNewRecord,
        array $changedAttributes,
        PageRecord $pageRecord
    ): void {
        if ($isPageNewRecord) {
            $form = new UrlCreateForm();
        } else {
            $form = new UrlUpdateForm();
        }
        $form->url = $pageRecord->url;
        $form->module_name = PagesModule::ID;
        $form->controller_name = PagesModule::FRONTEND_CONTROLLER_NAME;
        $form->action_name = PagesModule::FRONTEND_CONTROLLER_ACTION;
        $form->entity_id = (string) $pageRecord->id;
        if ($isPageNewRecord) {
            $this->_urlIndexService->addUrlToIndex($form);
        } else if (array_key_exists('url', $changedAttributes)) {
            $this->_urlIndexService->updateUrlInIndex($form);
        }
    }

    private function _getPageContent(int $pageId): string
    {
        $file = $this->_getPageContentFilePath($pageId);
        if (file_exists($file)) {
            return file_get_contents($file);
        } else {
            return '';
        }
    }

    private function _savePageContent(int $pageId, string $content): void
    {
        $file = $this->_getPageContentFilePath($pageId);
        file_put_contents($file, $content);
    }

    private function _deletePageContent(int $pageId): void
    {
        $file = $this->_getPageContentFilePath($pageId);
        if (file_exists($file)) {
            unlink($file);
        }
    }

    private function _getPageContentFilePath(int $pageId): string
    {
        $fileDirectory = $this->_contentStoragePath;
        $this->_fileHelper->createDirectoryIfNotExist($fileDirectory);
        $file = $fileDirectory . DIRECTORY_SEPARATOR . $pageId . '.txt';
        return $file;
    }
}