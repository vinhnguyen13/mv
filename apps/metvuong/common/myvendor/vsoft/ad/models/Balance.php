<?php
namespace vsoft\ad\models;

use frontend\models\Balance as Bl;
use frontend\models\Transaction;

class Balance extends Bl {
	
	const CHARGE_OK = 0;
	const CHARGE_NOT_ENOUGH = 1;
	const CHARGE_TRANSACTION_ERROR = 2;
	
	public function charge($model, $update, $chargeFee, $objectType, $action = 'save', $actionParams = [false]) {
		if($this->amount < $chargeFee) {
			return self::CHARGE_NOT_ENOUGH;
		}
		
		$backupValue = [];
		
		foreach ($update as $attr => $value) {
			$backupValue[$attr] = $model->$attr;
		}
		
		$transaction = $this->getDb()->beginTransaction();
		try {
			
			foreach ($update as $attr => $value) {
				$model->$attr = $value;
			}
			
			call_user_func_array([$model, $action], $actionParams);
			
			$this->amount -= $chargeFee;
			
			$connection = \Yii::$app->db;
			$connection->createCommand("UPDATE `ec_balance` SET `amount` = `amount` - " . $chargeFee . " WHERE `id` = " . $this->id)->execute();
				
			$transactionCode = md5(uniqid(rand(), true));
			Transaction::me()->saveTransaction($transactionCode, [
				'code' => $transactionCode,
				'user_id' => $this->user_id,
				'object_id' => $model->id,
				'object_type' => $objectType,
				'amount' => - $chargeFee,
				'balance' => $this->amount,
				'status' => Transaction::STATUS_SUCCESS,
			]);
		
			$transaction->commit();
			
			return self::CHARGE_OK;
		} catch(Exception $e) {
			$transaction->rollback();
			
			foreach ($backupValue as $attr => $value) {
				$model->$attr = $value;
			}
			
			return self::CHARGE_TRANSACTION_ERROR;
		}
	}
}