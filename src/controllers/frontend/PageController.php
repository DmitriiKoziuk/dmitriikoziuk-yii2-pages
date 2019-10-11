<?php declare(strict_types=1);

namespace DmitriiKoziuk\yii2Pages\controllers\frontend;

use yii\web\Controller;
use yii\web\GoneHttpException;
use yii\web\NotFoundHttpException;
use DmitriiKoziuk\yii2UrlIndex\forms\UrlUpdateForm;
use DmitriiKoziuk\yii2Pages\forms\PageCreateForm;
use DmitriiKoziuk\yii2Pages\services\PageService;
use DmitriiKoziuk\yii2Pages\exceptions\PageNotFoundException;

final class PageController extends Controller
{
    /**
     * @var PageService
     */
    private $pageService;

    public function __construct(
        $id,
        $module,
        PageService $pageService,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->pageService = $pageService;
    }

    /**
     * @param UrlUpdateForm $url
     * @return string
     * @throws GoneHttpException
     * @throws NotFoundHttpException
     */
    public function actionIndex(UrlUpdateForm $url)
    {
        try {
            $page = $this->pageService->getPageById((int) $url->entity_id);
        } catch (PageNotFoundException $e) {
            throw new NotFoundHttpException('Page not found');
        }
        if ($page->is_active === PageCreateForm::NOT_ACTIVE) {
            throw new GoneHttpException('Page are not accessible in this time.');
        }
        return $this->render('index', [
            'page' => $page,
        ]);
    }
}