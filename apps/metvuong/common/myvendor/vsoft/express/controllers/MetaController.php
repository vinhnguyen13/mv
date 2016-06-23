<?php

namespace vsoft\express\controllers;

use vsoft\express\models\Metadata;
use vsoft\express\models\MetadataSearch;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MetaController implements the CRUD actions for LcMeta model.
 */
class MetaController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all LcMeta models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MetadataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LcMeta model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new LcMeta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Metadata();
        if(!empty($_POST)){
            $data = Yii::$app->request->post();
            $seo = [
                'og:description' => $data["description"],
                'og:url'=>$data["Metadata"]["url"],
            ];
            unset($data["_csrf"]);
            unset($data["Metadata"]);

            $arr_data = array_merge($data, $seo, [
                'fb:app_id' => '119696758407282',
                'og:site_name' => 'Metvuong',
                'og:type' => 'website',
                'og:locale' => 'vi_VN',
                'og:locale:alternate' => 'en_US',
            ]);

            $metadata = Json::encode($arr_data);
            $model->metadata = $metadata;

        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing LcMeta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $metadata = $model->metadata;

        if(!empty($_POST)){
            $data = Yii::$app->request->post();
            $seo = [
                'og:description' => $data["description"],
                'og:url'=>$data["Metadata"]["url"],
            ];
            unset($data["_csrf"]);
            unset($data["Metadata"]);

            $arr_data = array_merge($data, $seo, [
                'fb:app_id' => '119696758407282',
                'og:site_name' => 'Metvuong',
                'og:type' => 'article',
                'og:locale' => 'vi_VN',
                'og:locale:alternate' => 'en_US',
            ]);

            $metadata = Json::encode($arr_data);
            $model->metadata = $metadata;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing LcMeta model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the LcMeta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Metadata the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Metadata::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
