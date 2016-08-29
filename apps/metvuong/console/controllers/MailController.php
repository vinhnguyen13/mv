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

	/**
	 * Send mail welcome to agent, auto register account
	 */
	public function actionSendMailContact(){
		$limit = $this->limit == null ? 100 : ((intval($this->limit) <= 100 && intval($this->limit) > 0) ? intval($this->limit) : 0);
		Mail::me()->welcomeAgent($this->code, $limit);
	}

	/**
	 * How use dashboard
	 */
	public function actionHowUseDashboard(){
		$limit = $this->limit == null ? 100 : ((intval($this->limit) <= 100 && intval($this->limit) > 0) ? intval($this->limit) : 0);
		Mail::me()->howUseDashboard($limit);
	}
}