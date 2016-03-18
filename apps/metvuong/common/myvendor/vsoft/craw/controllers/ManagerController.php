<?php
namespace vsoft\craw\controllers;

use common\components\Util;
use common\models\AdBuildingProject;
use common\models\AdInvestor;
use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use vsoft\craw\models\AdProductSearch;

class ManagerController extends Controller {
	public function actionIndex() {
		$adProduct = new AdProductSearch();
		$provider = $adProduct->search(\Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'filterModel' => $adProduct,
			'dataProvider' => $provider,
		]);
	}

    public function actionCopyProject(){
        $countProjectPri = AdBuildingProject::find()->count('id');
        if($countProjectPri < 15){
            $dbNameCraw = Util::me()->getDsnAttribute('dbname', Yii::$app->get('dbCraw')->dsn);
            $dbNamePri = Util::me()->getDsnAttribute('dbname', Yii::$app->get('db')->dsn);

            $sql= "INSERT `".$dbNamePri."`.`".AdBuildingProject::tableName()."` SELECT * FROM `".$dbNameCraw."`.`".AdBuildingProject::tableName()."`;";
            $return = Yii::$app->get('db')->createCommand($sql);
            if(!empty($return)){
                return "Copied data to Building Project";
            } else {
                return "Error: in copying...";
            }
        } else {
            return "Data exists so don't copy Building Project";
        }
    }

    public function actionCopyInvestor(){
        $countProjectPri = AdInvestor::find()->count('id');
        if($countProjectPri < 15){
            $dbNameCraw = Util::me()->getDsnAttribute('dbname', Yii::$app->get('dbCraw')->dsn);
            $dbNamePri = Util::me()->getDsnAttribute('dbname', Yii::$app->get('db')->dsn);

            $sql= "INSERT `".$dbNamePri."`.`".AdInvestor::tableName()."` SELECT * FROM `".$dbNameCraw."`.`".AdInvestor::tableName()."`;";
            $return = Yii::$app->get('db')->createCommand($sql);
            if(!empty($return)){
                return "Copied data to AdInvestor Project";
            } else {
                return "Error: in copying...";
            }
        } else {
            return "Data exists so don't copy AdInvestor Project";
        }
    }
}