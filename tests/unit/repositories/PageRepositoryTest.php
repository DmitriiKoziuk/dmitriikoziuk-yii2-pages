<?php namespace DmitriiKoziuk\yii2Pages\tests;

use Yii;
use yii\di\Container;
use DmitriiKoziuk\yii2Pages\tests\_fixtures\PageFixture;
use DmitriiKoziuk\yii2Pages\tests\_fixtures\UrlFixture;
use DmitriiKoziuk\yii2Pages\entities\PageEntity;
use DmitriiKoziuk\yii2Pages\repositories\PageRepository;

class PageRepositoryTest extends \Codeception\Test\Unit
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

    public function testMethodGetPageByIdReturnPageEntity()
    {
        $this->tester->seeRecord(PageEntity::class, ['id' => 1]);

        $pageRepository = new PageRepository();
        $pageEntity = $pageRepository->getPageById(1);

        $this->assertInstanceOf(PageEntity::class, $pageEntity);
        $this->assertEquals(1, $pageEntity->id);
    }

    public function testMethodGetPageByIdReturnNull()
    {
        $this->tester->dontSeeRecord(PageEntity::class, ['id' => 0]);

        $pageRepository = new PageRepository();
        $pageEntity = $pageRepository->getPageById(0);

        $this->assertEmpty($pageEntity);
    }
}