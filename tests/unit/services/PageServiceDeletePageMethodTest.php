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
use DmitriiKoziuk\yii2Pages\services\PageService;
use DmitriiKoziuk\yii2Pages\exceptions\PageNotFoundException;

class PageServiceDeletePageMethodTest extends Unit
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

    /**
     * @throws \DmitriiKoziuk\yii2Base\exceptions\ExternalComponentException
     * @throws \DmitriiKoziuk\yii2Pages\exceptions\PageNotFoundException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     * @throws \Throwable
     */
    public function testDeleteNonExistPage()
    {
        /** @var PageService $pageService */
        $pageService = Yii::$container->get(PageService::class);
        $this->expectException(PageNotFoundException::class);
        $pageService->deletePage(0);
    }

    /**
     * @throws \DmitriiKoziuk\yii2Base\exceptions\ExternalComponentException
     * @throws \DmitriiKoziuk\yii2Pages\exceptions\PageNotFoundException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     * @throws \Throwable
     */
    public function testPageDeleteSuccessful()
    {
        $this->tester->seeRecord(PageEntity::class, ['id' => 1]);
        $this->tester->seeRecord(UrlEntity::class, [
            'module_name'     => PagesModule::ID,
            'controller_name' => PagesModule::FRONTEND_CONTROLLER_NAME,
            'action_name'     => PagesModule::FRONTEND_CONTROLLER_ACTION,
            'entity_id'       => '1',
        ]);

        /** @var PageService $pageService */
        $pageService = Yii::$container->get(PageService::class);
        $pageService->deletePage(1);

        $this->tester->dontSeeRecord(PageEntity::class, ['id' => 1]);
        $this->tester->dontSeeRecord(UrlEntity::class, [
            'module_name'     => PagesModule::ID,
            'controller_name' => PagesModule::FRONTEND_CONTROLLER_NAME,
            'action_name'     => PagesModule::FRONTEND_CONTROLLER_ACTION,
            'entity_id'       => '1',
        ]);
    }
}