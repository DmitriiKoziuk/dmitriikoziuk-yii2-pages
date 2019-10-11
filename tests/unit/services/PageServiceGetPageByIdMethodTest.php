<?php declare(strict_types=1);

namespace DmitriiKoziuk\yii2Pages\tests;

use Yii;
use yii\di\Container;
use DmitriiKoziuk\yii2Pages\tests\_fixtures\PageFixture;
use DmitriiKoziuk\yii2Pages\tests\_fixtures\UrlFixture;
use DmitriiKoziuk\yii2Pages\entities\PageEntity;
use DmitriiKoziuk\yii2Pages\forms\PageUpdateForm;
use DmitriiKoziuk\yii2Pages\services\PageService;
use DmitriiKoziuk\yii2Pages\exceptions\PageNotFoundException;

class PageServiceGetPageByIdMethodTest extends \Codeception\Test\Unit
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
     * @throws PageNotFoundException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function testNotExist(): void
    {
        $pageId = 0;
        $this->tester->dontSeeRecord(PageEntity::class, ['id' => $pageId]);

        /** @var PageService $pageService */
        $pageService = Yii::$container->get(PageService::class);

        $this->expectException(PageNotFoundException::class);
        $pageService->getPageById($pageId);
    }

    /**
     * @throws PageNotFoundException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function testPageExist(): void
    {
        $pageId = 1;
        $this->tester->seeRecord(PageEntity::class, ['id' => $pageId]);

        /** @var PageService $pageService */
        $pageService = Yii::$container->get(PageService::class);
        $returnData = $pageService->getPageById($pageId);

        $this->assertNotEmpty($returnData);
        $this->assertInstanceOf(PageUpdateForm::class, $returnData);
        $this->assertEquals($pageId, $returnData->id);
    }
}