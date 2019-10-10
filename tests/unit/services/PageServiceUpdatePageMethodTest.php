<?php declare(strict_types=1);

namespace DmitriiKoziuk\yii2Pages\tests;

use Yii;
use yii\di\Container;
use Codeception\Test\Unit;
use DmitriiKoziuk\yii2UrlIndex\entities\UrlEntity;
use DmitriiKoziuk\yii2Pages\tests\_fixtures\PageFixture;
use DmitriiKoziuk\yii2Pages\tests\_fixtures\UrlFixture;
use DmitriiKoziuk\yii2Pages\PagesModule;
use DmitriiKoziuk\yii2Pages\entities\PageEntity;
use DmitriiKoziuk\yii2Pages\forms\PageUpdateForm;
use DmitriiKoziuk\yii2Pages\exceptions\PageNotFoundException;
use DmitriiKoziuk\yii2Pages\exceptions\PageUpdateFormNotValidException;
use DmitriiKoziuk\yii2Pages\services\PageService;

class PageServiceUpdatePageMethodTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public function _fixtures()
    {
        return [
            'page' => [
                'class' => PageFixture::class,
                'dataFile' => codecept_data_dir() . 'page_data.php'
            ],
            'url' => [
                'class' => UrlFixture::class,
                'dataFile' => codecept_data_dir() . 'url_data.php'
            ],
        ];
    }

    protected function _after()
    {
        Yii::$container = new Container();
    }

    public function testPageUpdateFormNotValid()
    {
        $form = new PageUpdateForm();
        /** @var PageService $pageService */
        $pageService = Yii::$container->get(PageService::class);

        $this->expectException(PageUpdateFormNotValidException::class);
        $pageService->updatePage($form);
    }

    public function testUpdatedPageNotExist()
    {
        /** @var PageService $pageService */
        $pageService = Yii::$container->get(PageService::class);
        $form = new PageUpdateForm([
            'id' => 0,
            'name' => 'Some fake name',
        ]);

        $this->assertTrue($form->validate());
        $this->expectException(PageNotFoundException::class);
        $pageService->updatePage($form);
    }

    public function testPageUpdateSuccessful()
    {
        $updatedPageAttributes = [
            'id' => 1,
            'name' => 'New page name',
        ];
        $this->tester->seeRecord(PageEntity::class, [
            'id' => 1,
        ]);
        $this->tester->dontSeeRecord(PageEntity::class, $updatedPageAttributes);
        $this->tester->dontSeeRecord(UrlEntity::class, [
            'url' => '/New-page-name',
            'module_name' => PagesModule::ID,
            'controller_name' => PagesModule::FRONTEND_CONTROLLER_NAME,
            'action_name' => PagesModule::FRONTEND_CONTROLLER_ACTION,
            'entity_id' => '1',
        ]);

        /** @var PageService $pageService */
        $pageService = Yii::$container->get(PageService::class);
        $form = new PageUpdateForm($updatedPageAttributes);
        $returnData = $pageService->updatePage($form);

        $this->tester->seeRecord(PageEntity::class, $updatedPageAttributes);
        $this->tester->seeRecord(UrlEntity::class, [
            'url' => '/New-page-name',
            'module_name' => PagesModule::ID,
            'controller_name' => PagesModule::FRONTEND_CONTROLLER_NAME,
            'action_name' => PagesModule::FRONTEND_CONTROLLER_ACTION,
            'entity_id' => '1',
        ]);
        $this->assertNotEmpty($returnData);
        $this->assertInstanceOf(PageUpdateForm::class, $returnData);
        $this->assertEquals(
            $updatedPageAttributes,
            $returnData->getAttributes(['id', 'name'])
        );
    }
}