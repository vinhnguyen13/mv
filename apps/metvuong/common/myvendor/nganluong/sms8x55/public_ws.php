<?php
	define('PASSCODE', '123456');
	define('NS','NS');
	
	require_once('lib/nusoap.php');
		
	function BillUpdate($reciver_email, $transaction_id, $price, $amount, $fee, $ref_code, $keyword, $service_id, $message, $client_mobile, $telco, $checksum)
	{
		$params = array(
			'reciver_email'	=> $reciver_email,
			'transaction_id'=> $transaction_id,
			'price'			=> $price,
			'amount'		=> $amount,
			'fee'			=> $fee,
			'ref_code'		=> $ref_code,	
			'keyword'		=> $keyword,
			'service_id'	=> $service_id,
			'message'		=> $message,
			'client_mobile'	=> $client_mobile,
			'telco'			=> $telco,
			'checksum'		=> $checksum,
		);
		writeLog(dirname(__FILE__).'/log/test_ws.txt', date('H:i:s, d/m/Y:').serialize($params));
		//---------
		$result = -1;
		$temp = getChecksum($params, PASSCODE);
		if ($temp == $checksum) {
			$result = 1;
		} else {
			$result = 0;
		}
		// return
		return $result;
	}
	
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
	
	function writeLog($filename, $content)
	{
		$br = chr(10).chr(13);
		$content.= $br;
		$file = fopen($filename, 'a+');
		fwrite($file, $content);
		fclose($file);
		return true;
	}
	
	// set--------
	$server = new nusoap_server();
	$server->configureWSDL('WS_WITH_SMS',NS);
	$server->wsdl->schemaTargetNamespace=NS;
	$server->register('BillUpdate',array(
										'reciver_email'=>'xsd:string',
										'transaction_id'=>'xsd:string',
										'price'=>'xsd:string',
										'amount'=>'xsd:string',
										'fee'=>'xsd:string',
										'ref_code'=>'xsd:string',
										'keyword'=>'xsd:string',
										'service_id'=>'xsd:string',
										'message'=>'xsd:string',
										'client_mobile'=>'xsd:string',
										'telco'=>'xsd:string',
										'checksum'=>'xsd:string'
									),array('result'=>'xsd:string'),NS);
	
	// Khoi tao Webservice
	$HTTP_RAW_POST_DATA = (isset($HTTP_RAW_POST_DATA)) ? $HTTP_RAW_POST_DATA :'';
	$server->service($HTTP_RAW_POST_DATA);
?>