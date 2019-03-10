<?php
namespace DmitriiKoziuk\yii2Pages\controllers\frontend;

use yii\base\Module;
use yii\base\ViewNotFoundException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use DmitriiKoziuk\yii2CustomUrls\data\UrlData;
use DmitriiKoziuk\yii2Pages\services\PageService;

final class PageController extends Controller
{
    /**
     * @var PageService
     */
    private $_pageService;

    public function __construct(
        string $id,
        Module $module,
        PageService $pageService,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->_pageService = $pageService;
    }

    public function actionIndex(UrlData $urlData)
    {
        $pageEntity = $this->_pageService->getPageById($urlData->getEntityId());
        if (empty($pageEntity)) {
            throw new NotFoundHttpException('Page not found.');
        }
        try {
            return $this->render($pageEntity->getSlug(), [
                'pageEntity' => $pageEntity,
            ]);
        } catch (ViewNotFoundException $e) {
            return $this->render('index', [
                'pageEntity' => $pageEntity,
            ]);
        }
    }
}