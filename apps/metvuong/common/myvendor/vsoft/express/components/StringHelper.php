<?php
namespace vsoft\express\components;

use yii\helpers\StringHelper as SH;
class StringHelper extends SH {
	public static function previousTime($Time) {
		$StartDay = mktime( 0, 0, 0 );
		$Yesterday = $StartDay - 86400;
		$Current = time();
		if( $Time < $Yesterday ) {
			$PreviousDay = floor( ( $Yesterday - $Time ) / 86400 ) + 2;
			$String = sprintf(\Yii::t('ad', '%s ngày trước'), $PreviousDay);
		} elseif( $Time < $StartDay ) {
			$PreviousTime = date( 'H:i', $Time );
			$String = sprintf(\Yii::t('ad', 'hôm qua, lúc %s'), $PreviousTime);
		} else {
	
			$Afternoon = $StartDay + 46799;
	
			if( ( $Current > $Afternoon && $Time > $Afternoon ) || ( $Current < $Afternoon && $Time < $Afternoon ) ) {
				$PreviousTime = $Current - $Time;
				if( $PreviousTime <= 60 ) {
					$String = \Yii::t('ad', 'mới đây');
				} else {
					$Hours = floor( $PreviousTime / 3600 );
					$Minutes = floor( ( $PreviousTime % 3600 ) / 60 );
					if( $Hours == 0 )
						$String = sprintf(\Yii::t('ad', '%s phút trước'), $Minutes);
					else {
						if( 0 == $Minutes )
							$String = sprintf(\Yii::t('ad', '%s giờ trước'), $Hours);
						else
							$String = sprintf(\Yii::t('ad', '%s giờ %s phút trước'), $Hours, $Minutes);
					}
				}
			} else {
				$PreviousTime = date( 'h:i', $Time );
				$String = sprintf(\Yii::t('ad', 'hôm nay, lúc %s'), $PreviousTime);
			}
	
		}
		return $String;
	}
	
	public static function formatNumber($number) {
		$split = explode('.', $number);
		
		$integer = $split[0];
		
		$integer = number_format($integer, 0, ',', '.');
		
		if(isset($split[1])) {
			$integer = $integer . ',' . $split[1];
		}
		
		return $integer;
	}
	
	public static function formatCurrency($number) {
		if($number > 999999999) {
			$currency = $number / 1000000000;
			$currency = self::formatNumber($currency) . ' <span>' . \Yii::t('ad', 'billion').'</span>';
		} else if($number > 999999) {
			$currency = $number / 1000000;
			$currency = self::formatNumber($currency) . ' <span>' . \Yii::t('ad', 'million').'</span>';
		} else {
			$currency = self::formatNumber($number);
		}
		
		return $currency;
	}
}