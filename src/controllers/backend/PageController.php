<?php

namespace DmitriiKoziuk\yii2Pages\controllers\backend;

use Yii;
use yii\base\Module;
use yii\web\Controller;
use yii\filters\VerbFilter;
use DmitriiKoziuk\yii2Pages\records\PageRecordSearch;
use DmitriiKoziuk\yii2Pages\forms\PageInputForm;
use DmitriiKoziuk\yii2Pages\services\PageService;

/**
 * PageController implements the CRUD actions for Page model.
 */
final class PageController extends Controller
{
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

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PageRecordSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Page model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'pageEntity' => $this->_pageService->getPageById($id),
        ]);
    }

    public function actionCreate()
    {
        $pageInputForm = new PageInputForm();
        if (
            Yii::$app->request->isPost &&
            $pageInputForm->load(Yii::$app->request->post()) &&
            $pageInputForm->validate()
        ) {
            $pageEntity = $this->_pageService->createPage($pageInputForm);
            return $this->redirect(['view', 'id' => $pageEntity->getId()]);
        }
        return $this->render('create', [
            'pageInputForm' => $pageInputForm,
        ]);
    }

    public function actionUpdate($id)
    {
        $pageInputForm = new PageInputForm();
        if (
            Yii::$app->request->isPost &&
            $pageInputForm->load(Yii::$app->request->post()) &&
            $pageInputForm->validate()
        ) {
            $pageEntity = $this->_pageService->updatePage($id, $pageInputForm);
            return $this->redirect(['view', 'id' => $pageEntity->getId()]);
        } else {
            $pageEntity = $this->_pageService->getPageById($id);
            $pageInputForm = $pageEntity->getInputForm();
        }
        return $this->render('update', [
            'pageInputForm' => $pageInputForm,
            'pageEntity' => $pageEntity,
        ]);
    }

    public function actionDelete($id)
    {
        $this->_pageService->deletePage($id);
        return $this->redirect(['index']);
    }
}
