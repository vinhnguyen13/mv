<?php
namespace frontend\controllers;

use frontend\components\Controller;
//use vsoft\buildingProject\models\BuildingProject;
use frontend\models\Elastic;
use vsoft\ad\models\AdProduct;
use Yii;
use yii\data\Pagination;
use vsoft\ad\models\AdBuildingProject;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use yii\web\Response;
use vsoft\ad\models\AdDistrict;
use vsoft\ad\models\AdWard;
use vsoft\ad\models\AdStreet;

class BuildingProjectController extends Controller
{
	public $layout = '@app/views/layouts/layout';

    /**
     * @return string
     */
    public function beforeAction($action)
    {
        $this->view->params['menuProject'] = true;
        return parent::beforeAction($action);
    }

	function actionIndex() {
        $this->view->title = Yii::t('meta', 'du-an');
        $models = AdBuildingProject::find()->where('`status` = ' . AdBuildingProject::STATUS_ENABLED)
            ->andWhere('`city_id` is not null')
            ->orderBy(['hot_project' => SORT_DESC, 'district_id' => SORT_ASC, 'city_id'=> SORT_ASC, 'id' => SORT_DESC]);
        $count = $models->count();
        $pagination = new Pagination(['totalCount' => $count]);
        $pagination->defaultPageSize = 12;
        $models = $models->offset($pagination->offset)->limit($pagination->limit)->all();
        return $this->render('index', ['models' => $models, 'pagination' => $pagination]);

	}
	
	function actionView($slug) {
		$model = AdBuildingProject::find()->where('`slug` = :slug', [':slug' => $slug])->one();
		if(count($model) > 0) {
            $click = $model->click;
            $model->click = $click + 1;
            $model->update();
            return $this->render('view', ['model' => $model]);
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	function actionRedirectView($id) {
		$model = AdBuildingProject::find()->where('`id` = :id', [':id' => $id])->one();
        if($model) {
            $this->redirect(Url::to(['building-project/view', 'slug' => $model->slug]));
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
	}

    function actionDetail($id) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = AdBuildingProject::find()->where('`id` = :id', [':id' => $id])->asArray(true)->one();
        
        $response = [
        	'url' => Url::to(['building-project/view', 'slug' => $model['slug']]),
        	'city_id' => $model['city_id'],
        	'district_id' => $model['district_id'],
        	'ward_id' => $model['ward_id'],
        	'street_id' => $model['street_id'],
        	'districts' => AdDistrict::getListByCity($model['city_id']),
        	'wards' => AdWard::getListByDistrict($model['district_id']),
        	'streets' => AdStreet::getListByDistrict($model['district_id']),
        	'home_no' => $model['home_no'],
        	'lat' => $model['lat'],
        	'lng' => $model['lng'],
        	'facilities' => $model['facilities']
        ];

        return $response;
	}

    function actionFind() {
        $name = \Yii::$app->request->get("project_name");
        $models = AdBuildingProject::find()->where('`status` = ' . AdBuildingProject::STATUS_ENABLED)
            ->andWhere('`city_id` is not null')
            ->orderBy(['city_id'=> SORT_ASC, 'id' => SORT_DESC]);
        if(!empty($name)) {
            $models = $models->andWhere('name LIKE :query')->addParams([':query' => '%' . $name . '%']);
        }
        $count = $models->count();
        $pagination = new Pagination(['totalCount' => $count]);
        $pagination->defaultPageSize = 12;
        $models = $models->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index', ['models' => $models, 'project_name' => $name, 'pagination' => $pagination]);
    }

    public function actionSearch() {
        $v = \Yii::$app->request->get('v');

        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $response = [];

            $result = Elastic::searchAllProjects($v);

            if($result['hits']['total'] == 0) {
                $result = Elastic::searchAllProjects(Elastic::transform($v));
            }

            foreach ($result['hits']['hits'] as $k => $hit) {
                $response[$k]['full_name'] = $hit['_source']['full_name'];
                $response[$k]['id'] = $hit['_id'];
                $response[$k]['link'] = Url::to(["building-project/view", 'slug' => $hit['_source']['slug'] ]);
            }

            return $response;

        } else {
            $models = AdBuildingProject::find()->where('`status` = ' . AdBuildingProject::STATUS_ENABLED)
                ->andWhere('`city_id` is not null')
                ->orderBy(['city_id'=> SORT_ASC, 'id' => SORT_DESC]);
            if(!empty($v)) {
                $models = $models->andWhere('name LIKE :query')->addParams([':query' => '%' . $v . '%']);
            }
            $count = $models->count();
            $pagination = new Pagination(['totalCount' => $count]);
            $pagination->defaultPageSize = 12;
            $models = $models->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();
            return $this->render('index', ['models' => $models, 'project_name' => $v, 'pagination' => $pagination]);
        }
    }

    public function actionLoadSidebar($limit = 10, $current_id = null){
        $topproject = \vsoft\ad\models\AdBuildingProject::getTopProject($limit, $current_id);
        if(count($topproject) > 0)
            echo $this->renderAjax('/building-project/_partials/topproject',['projects' => $topproject]);
    }

}