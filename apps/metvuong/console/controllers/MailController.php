<?php
namespace console\controllers;

use console\models\Mail;
use Yii;
use yii\console\Controller;
use frontend\models\Elastic;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class MailController extends Controller {
	public $code;
	public $limit;
	public function options()
	{
		return ['code', 'limit'];
	}
	public function optionAliases()
	{
		return ['code' => 'code', 'limit' => 'limit'];
	}

	public function actionSendMailContact(){
		$limit = $this->limit == null ? 100 : ((intval($this->limit) <= 100 && intval($this->limit) > 0) ? intval($this->limit) : 0);
		Mail::sendMailContact($this->code, $limit);
	}
}