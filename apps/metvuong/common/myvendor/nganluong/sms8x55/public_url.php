<?php
	define('PASSCODE', '123456');
	
	$data = serialize($_REQUEST);
	writeFileLog('./#log/log.txt', $data);
	
	if (@$_REQUEST['function'] == 'BillUpdate') {
		$checksum = getChecksum($_REQUEST, PASSCODE);
		if ($checksum == @$_REQUEST['checksum']) {			
			echo '1';
			die();
		} else {
			echo '0';
			die();
		}
	}
	echo '0';
	
	function getChecksum($params, $password)
	{
		$md5 = array();
		$map = getMap();
		foreach ($map as $key) {
			$md5[$key] = $params[$key];
		}
		$md5 = implode('|', $md5).'|'.$password;
		return md5($md5);
	}
	
	function getMap()
	{
		return array(
			'reciver_email',
			'transaction_id',
			'price',
			'amount',
			'fee',
			'ref_code',
			'keyword',
			'service_id',
			'message',
			'client_mobile',
			'telco',
		);
	}
	
	function writeFileLog($file_name, $data)
	{
		$fp = fopen($file_name,'a');
		if ($fp) {
			$line = date("H:i:s, d/m/Y:  ",time()).$data. " \n";
			fwrite($fp,$line);
			fclose($fp);
		}
	}
?>