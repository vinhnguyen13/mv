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
		$this->limit = !empty($this->limit) ? $this->limit : 100;
		Mail::me()->welcomeAgent($this->code, $this->limit);
	}

	/**
	 * How use dashboard
	 */
	public function actionHowUseDashboard(){
		$this->limit = !empty($this->limit) ? $this->limit : 100;
		Mail::me()->howUseDashboard($this->limit);
	}
}