<?php
namespace DmitriiKoziuk\yii2Pages\controllers\frontend;

use yii\base\Module;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use DmitriiKoziuk\yii2CustomUrls\data\UrlData;
use DmitriiKoziuk\yii2Pages\services\PageService;

class PageController extends Controller
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
        $pageEntity = $this->_pageService->getPageById($urlData->entity_id);
        if (empty($pageEntity)) {
            throw new NotFoundHttpException('Page not found.');
        }
        return $this->render('index', [
            'pageEntity' => $pageEntity,
        ]);
    }
}