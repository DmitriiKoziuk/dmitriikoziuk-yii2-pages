<?php declare(strict_types=1);

namespace DmitriiKoziuk\yii2Pages\services;

use yii\db\Connection;
use DmitriiKoziuk\yii2Base\helpers\UrlHelper;
use DmitriiKoziuk\yii2Base\services\DBActionService;
use DmitriiKoziuk\yii2Base\traits\ModelValidatorTrait;
use DmitriiKoziuk\yii2Base\exceptions\DataNotValidException;
use DmitriiKoziuk\yii2Base\exceptions\ExternalComponentException;
use DmitriiKoziuk\yii2UrlIndex\forms\UrlCreateForm;
use DmitriiKoziuk\yii2UrlIndex\forms\UrlUpdateForm;
use DmitriiKoziuk\yii2UrlIndex\forms\RemoveEntityForm;
use DmitriiKoziuk\yii2UrlIndex\forms\UpdateEntityUrlForm;
use DmitriiKoziuk\yii2UrlIndex\interfaces\UrlIndexServiceInterface;
use DmitriiKoziuk\yii2Pages\PagesModule;
use DmitriiKoziuk\yii2Pages\exceptions\PageCreateFormNotValid;
use DmitriiKoziuk\yii2Pages\exceptions\PageNotFoundException;
use DmitriiKoziuk\yii2Pages\exceptions\PageUpdateFormNotValidException;
use DmitriiKoziuk\yii2Pages\interfaces\PageRepositoryInterface;
use DmitriiKoziuk\yii2Pages\interfaces\PageServiceInterface;
use DmitriiKoziuk\yii2Pages\entities\PageEntity;
use DmitriiKoziuk\yii2Pages\forms\PageCreateForm;
use DmitriiKoziuk\yii2Pages\forms\PageUpdateForm;

class PageService extends DBActionService implements PageServiceInterface
{
    use ModelValidatorTrait;

    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @var UrlIndexServiceInterface
     */
    private $urlIndexService;

    public function __construct(
        PageRepositoryInterface $pageRepository,
        UrlIndexServiceInterface $urlIndexService,
        Connection $db = null
    ) {
        parent::__construct($db);
        $this->pageRepository = $pageRepository;
        $this->urlIndexService = $urlIndexService;
    }

    /**
     * @param PageCreateForm $pageCreateForm
     * @return PageUpdateForm
     * @throws DataNotValidException
     * @throws ExternalComponentException
     * @throws \Exception
     */
    public function createPage(PageCreateForm $pageCreateForm): PageUpdateForm
    {
        $this->validateModels(
            [$pageCreateForm],
            new PageCreateFormNotValid('Page create form not valid.')
        );

        try {
            $this->beginTransaction();
            $pageEntity = $this->savePage($pageCreateForm);
            $this->addPageUrlToIndex($pageEntity->name, $pageEntity->id);
            $this->commitTransaction();
            return new PageUpdateForm($pageEntity->getAttributes());
        } catch (\Exception $e) {
            $this->rollbackTransaction();
            throw $e;
        }
    }

    /**
     * @param int $pageId
     * @throws ExternalComponentException
     * @throws PageNotFoundException
     * @throws \Throwable
     */
    public function deletePage(int $pageId): void
    {
        $pageEntity = PageEntity::find()
            ->where(['id' => $pageId])
            ->one();

        if (empty($pageEntity)) {
            throw new PageNotFoundException("Page with id '{$pageId}' not found.");
        }

        try {
            $this->beginTransaction();
            $pageEntity->delete();
            $this->removePageUrlFromIndex($pageEntity->id);
            $this->commitTransaction();
        } catch (\Exception $e) {
            $this->rollbackTransaction();
            throw $e;
        }
    }

    public function updatePage(PageUpdateForm $pageUpdateForm): PageUpdateForm
    {
        $this->validateModels(
            [$pageUpdateForm],
            new PageUpdateFormNotValidException('Page update form not valid.')
        );

        $pageEntity = $this->pageRepository->getPageById($pageUpdateForm->id);
        if (is_null($pageEntity)) {
            throw new PageNotFoundException("Page with id '{$pageUpdateForm->id}' not found. Nothing to update.");
        }

        try {
            $this->beginTransaction();
            $pageEntity->setAttributes(
                $pageUpdateForm->getAttributes(
                    null,
                    ['id', 'created_at', 'updated_at']
                )
            );
            $this->pageRepository->save($pageEntity);
            $this->updatePageUrlInIndex($pageEntity->name, $pageEntity->id);
            $this->commitTransaction();
        } catch (\Exception $e) {
            $this->rollbackTransaction();
            throw $e;
        }

        return $pageUpdateForm;
    }

    /**
     * @param int $pageId
     * @return PageUpdateForm
     * @throws PageNotFoundException
     */
    public function getPageById(int $pageId): PageUpdateForm
    {
        $pageEntity = $this->pageRepository->getPageById($pageId);
        if (is_null($pageEntity)) {
            throw new PageNotFoundException("Page with id '{$pageId}' not found. Nothing to update.");
        }

        return new PageUpdateForm($pageEntity->getAttributes());
    }

    private function savePage(PageCreateForm $pageCreateForm): PageEntity
    {
        $pageEntity = new PageEntity($pageCreateForm->getAttributes());
        $this->pageRepository->save($pageEntity);
        return $pageEntity;
    }

    private function addPageUrlToIndex(string $pageName, int $pageId): UrlUpdateForm
    {
        $urlCreateForm = new UrlCreateForm();
        $urlCreateForm->url = '/' . UrlHelper::getSlugFromString($pageName);
        $urlCreateForm->module_name = PagesModule::ID;
        $urlCreateForm->controller_name = PagesModule::FRONTEND_CONTROLLER_NAME;
        $urlCreateForm->action_name = PagesModule::FRONTEND_CONTROLLER_ACTION;
        $urlCreateForm->entity_id = (string) $pageId;
        return $this->urlIndexService->addUrl($urlCreateForm);
    }

    private function removePageUrlFromIndex(int $pageId): void
    {
        $form = new RemoveEntityForm();
        $form->module_name = PagesModule::ID;
        $form->controller_name = PagesModule::FRONTEND_CONTROLLER_NAME;
        $form->action_name = PagesModule::FRONTEND_CONTROLLER_ACTION;
        $form->entity_id = (string) $pageId;
        $this->urlIndexService->removeEntityUrl($form);
    }

    private function updatePageUrlInIndex(string $pageName, int $pageId): UrlUpdateForm
    {
        $form = new UpdateEntityUrlForm();
        $form->url = '/' . UrlHelper::getSlugFromString($pageName);
        $form->module_name = PagesModule::ID;
        $form->controller_name = PagesModule::FRONTEND_CONTROLLER_NAME;
        $form->action_name = PagesModule::FRONTEND_CONTROLLER_ACTION;
        $form->entity_id = (string) $pageId;
        return $this->urlIndexService->updateEntityUrl($form);
    }
}