<?php declare(strict_types=1);

namespace DmitriiKoziuk\yii2Pages\tests;

use Yii;
use yii\di\Container;
use Codeception\Test\Unit;
use DmitriiKoziuk\yii2Base\exceptions\DataNotValidException;
use DmitriiKoziuk\yii2Base\exceptions\ExternalComponentException;
use DmitriiKoziuk\yii2UrlIndex\entities\UrlEntity;
use DmitriiKoziuk\yii2Pages\PagesModule;
use DmitriiKoziuk\yii2Pages\entities\PageEntity;
use DmitriiKoziuk\yii2Pages\forms\PageCreateForm;
use DmitriiKoziuk\yii2Pages\forms\PageUpdateForm;
use DmitriiKoziuk\yii2Pages\exceptions\PageCreateFormNotValid;
use DmitriiKoziuk\yii2Pages\services\PageService;

class PageServiceCreatePageMethodTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _after()
    {
        Yii::$container = new Container();
    }

    /**
     * @throws DataNotValidException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     * @throws ExternalComponentException
     */
    public function testPageCreateFormNotValid()
    {
        $pageCreateForm = new PageCreateForm();
        /** @var PageService $pageService */
        $pageService = Yii::$container->get(PageService::class);
        $this->expectException(PageCreateFormNotValid::class);
        $pageService->createPage($pageCreateForm);

    }

    /**
     * @throws DataNotValidException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     * @throws ExternalComponentException
     */
    public function testExecuteSuccessful()
    {
        $this->tester->dontSeeRecord(PageEntity::class, ['name' => 'Hello']);
        $this->tester->dontSeeRecord(UrlEntity::class, [
            'url' => '/hello',
        ]);

        $pageCreateForm = new PageCreateForm([
            'name' => 'Hello',
        ]);
        /** @var PageService $pageService */
        $pageService = Yii::$container->get(PageService::class);
        $serviceReturnData = $pageService->createPage($pageCreateForm);

        $this->tester->seeRecord(PageEntity::class, ['name' => 'Hello']);
        $this->tester->seeRecord(UrlEntity::class, [
            'url'             => '/hello',
            'module_name'     => PagesModule::ID,
            'controller_name' => PagesModule::FRONTEND_CONTROLLER_NAME,
            'action_name'     => PagesModule::FRONTEND_CONTROLLER_ACTION,
            'entity_id'       => $serviceReturnData->id,
        ]);
        $this->assertInstanceOf(PageUpdateForm::class, $serviceReturnData);
        $this->assertEquals(
            $pageCreateForm->getAttributes(),
            $serviceReturnData->getAttributes(null, [
                'id', 'created_at', 'updated_at'
            ])
        );
    }
}