<?php
namespace DmitriiKoziuk\yii2Pages;

use Yii;
use yii\base\BootstrapInterface;
use DmitriiKoziuk\yii2ConfigManager\ConfigManager;
use DmitriiKoziuk\yii2ConfigManager\services\ConfigService;
use DmitriiKoziuk\yii2ModuleManager\services\ModuleService;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        /** @var ConfigService $configService */
        $configService = Yii::$container->get(ConfigService::class);
        $app->setModule(PagesModule::ID, [
            'class' => PagesModule::class,
            'diContainer' => Yii::$container,
            'backendAppId' => $configService->getValue(
                ConfigManager::GENERAL_CONFIG_NAME,
                'backendAppId'
            ),
            'frontendAppId' => $configService->getValue(
                ConfigManager::GENERAL_CONFIG_NAME,
                'frontendAppId'
            ),
        ]);
        /** @var PagesModule $module */
        $module = $app->getModule(PagesModule::ID);
        /** @var ModuleService $moduleService */
        $moduleService = Yii::$container->get(ModuleService::class);
        $moduleService->registerModule($module);
    }
}