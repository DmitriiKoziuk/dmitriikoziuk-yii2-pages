<?php declare(strict_types=1);

namespace DmitriiKoziuk\yii2Pages;

use yii\di\Container;
use yii\web\Application as WebApp;
use yii\base\Application as BaseApp;
use yii\console\Application as ConsoleApp;
use DmitriiKoziuk\yii2ModuleManager\interfaces\ModuleInterface;
use DmitriiKoziuk\yii2ConfigManager\ConfigManagerModule;
use DmitriiKoziuk\yii2UrlIndex\UrlIndexModule;
use DmitriiKoziuk\yii2Pages\services\PageService;
use DmitriiKoziuk\yii2Pages\repositories\PageRepository;

class PagesModule extends \yii\base\Module implements ModuleInterface
{
    const ID = 'dk-pages';

    const TRANSLATE = self::ID;

    const FRONTEND_CONTROLLER_NAME = 'page';

    const FRONTEND_CONTROLLER_ACTION = 'index';

    /**
     * @var Container
     */
    public $diContainer;

    /**
     * Overwrite this param if you backend app id is different from default.
     * @var string
     */
    public $backendAppId;

    /**
     * Overwrite this param if you backend app id is different from default.
     * @var string
     */
    public $frontendAppId;

    public function init(): void
    {
        /** @var BaseApp $app */
        $app = $this->module;
        $this->_initLocalProperties($app);
        $this->_registerTranslation($app);
        $this->_registerClassesToDIContainer($app);
    }

    public static function getId(): string
    {
        return self::ID;
    }

    public function getBackendMenuItems(): array
    {
        return ['label' => 'Pages', 'url' => ['/' . self::ID . '/page/index']];
    }

    public static function requireOtherModulesToBeActive(): array
    {
        return [
            ConfigManagerModule::class,
            UrlIndexModule::class,
        ];
    }

    /**
     * @param BaseApp $app
     * @throws \InvalidArgumentException
     */
    private function _initLocalProperties(BaseApp $app): void
    {
        if (empty($this->backendAppId)) {
            throw new \InvalidArgumentException('Property backendAppId not set.');
        }
        if (empty($this->frontendAppId)) {
            throw new \InvalidArgumentException('Property frontendAppId not set.');
        }
        if ($app instanceof WebApp && $app->id == $this->backendAppId) {
            $this->controllerNamespace = __NAMESPACE__ . '\controllers\backend';
            $this->viewPath = '@DmitriiKoziuk/yii2Pages/views/backend';
        }
        if ($app instanceof WebApp && $app->id == $this->frontendAppId) {
            $this->controllerNamespace = __NAMESPACE__ . '\controllers\frontend';
            $this->viewPath = '@DmitriiKoziuk/yii2Pages/views/frontend';
        }
        if ($app instanceof ConsoleApp) {
            $app->controllerMap['migrate']['migrationNamespaces'][] = __NAMESPACE__ . '\migrations';
        }
    }

    private function _registerTranslation(BaseApp $app): void
    {
        $app->i18n->translations[self::TRANSLATE] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath'       => '@DmitriiKoziuk/yii2Pages/messages',
        ];
    }

    private function _registerClassesToDIContainer(BaseApp $app): void
    {
        $this->diContainer->setSingleton(PageService::class, function () {
            return new PageService(new PageRepository());
        });
    }
}