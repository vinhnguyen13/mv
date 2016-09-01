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
	
	public static function formatNumber($num, $round = false) {
		if(!is_numeric($num)) {
			return null;
		}
	
		if($round !== false) {
			$num = round($num, $round);
		}
	
		$parts = explode('.', $num);
	
		$parts[0] = number_format($parts[0]);
	
		return implode('.', $parts);
	}
	
	public static function formatCurrency($num, $round = 2, $roundBelowMillion = 0) {
		if(!is_numeric($num)) {
			return null;
		}
	
		if($num < 1000000) {
			return self::formatNumber($num, $roundBelowMillion);
		}
	
		$f = round(($num / 1000000), $round);
		$unit = \Yii::t('ad', 'million');
	
		if($f >= 1000) {
			$f = round(($f / 1000), $round);
			$unit = \Yii::t('ad', 'billion');
		}
	
		return self::formatNumber($f) . ' <span class="txt-unit">' . $unit . '</span>';
	}
}