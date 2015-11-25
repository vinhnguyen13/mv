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
    	AdInvestor::findOne($id)->delete();
    
    	return $this->redirect(['index']);
    }
}