<?php declare(strict_types=1);

namespace DmitriiKoziuk\yii2Pages\tests\acceptance;

use Yii;
use yii\di\Container;
use DmitriiKoziuk\yii2UrlIndex\entities\UrlEntity;
use DmitriiKoziuk\yii2Pages\tests\_fixtures\PageFixture;
use DmitriiKoziuk\yii2Pages\tests\_fixtures\UrlFixture;
use DmitriiKoziuk\yii2Pages\tests\AcceptanceTester;
use DmitriiKoziuk\yii2Pages\entities\PageEntity;

class PageControllerCest
{
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

    public function _after(AcceptanceTester $I)
    {
        Yii::$container = new Container();
    }

    /**
     * @env frontend
     * @param AcceptanceTester $I
     */
    public function tryIsPageLoad(AcceptanceTester $I)
    {
        $I->seeRecord(PageEntity::class, ['name' => 'Contact page']);
        $I->seeRecord(UrlEntity::class, ['url' => '/Contact-page']);
        $I->amOnPage('/Contact-page');
        $I->seeResponseCodeIs(200);
        $I->seeInTitle('Contact page');
        $I->see('You are on contact page.');
    }
}
