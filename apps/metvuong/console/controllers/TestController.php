<?php
namespace console\controllers;

use console\models\Test;
use Yii;
use yii\console\Controller;
use frontend\models\Elastic;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class TestController extends Controller {
    public function actionDuplicate($thread = 'th1', $start = 1, $end = 10)
    {
		Test::me()->duplicate($thread, $start, $end);
    }

}