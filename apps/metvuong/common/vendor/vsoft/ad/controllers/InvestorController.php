<?php
namespace vsoft\ad\controllers;

use Yii;
use yii\web\Controller;
use common\vendor\vsoft\ad\models\AdInvestor;

class InvestorController extends Controller
{
	public function actionCreate()
	{
		$model = new AdInvestor();
		$model->loadDefaultValues();
		
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
	}
	
	public function actionUpdate($id)
	{
		$model = AdInvestor::findOne($id);
		
		if($model) {
			if(Yii::$app->request->isPost) {
				if ($model->load(Yii::$app->request->post())) {
					$model->save();
					return $this->redirect('index');
				}
			}
			
			return $this->render('update', ['model' => $model,]);
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	public function actionIndex()
	{
		$searchModel = new AdInvestor();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
    

    public function actionDelete($id)
    {
    	Yii::$app->db->createCommand()->delete('ad_investor_building_project', 'investor_id = :investor_id', [':investor_id' => $id])->execute();
    	
    	AdInvestor::findOne($id)->delete();
    
    	return $this->redirect(['index']);
    }
    
    public function actionView($id)
    {
    	$model = AdInvestor::findOne($id);
    	 
    	if($model) {
    		return $this->render('view', ['model' => $model,]);
    	} else {
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}
    }
}