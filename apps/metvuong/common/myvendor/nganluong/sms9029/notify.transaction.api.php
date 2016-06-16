<?PHP

session_start(1);
date_default_timezone_set('Asia/Bangkok'); // Set Time Zone required tu PHP 5
require_once('lib/nusoap.php');

function notifySmsTransaction($request_id, $game_code, $telco, $mobile, $price, $amount, $command_code, $sms_message) {
    return 1;
}

function notifyMobileTransaction($request_id, $game_code, $telco, $mobile, $price, $amount, $command_code, $refer_code) {
    return 1;
}

function writeLog($fileName, $data, $breakLine = true, $addTime = true) {
    $fp = fopen($fileName, 'a+');
    if ($fp) {
        if ($breakLine) {
            if ($addTime)
                $line = date(
                                "H:i:s, d/m/Y:  ", time()) . $data . " \n";
            else
                $line = $data . " \n";
        } else {
            if ($addTime)
                $line = date("H:i:s, d/m/Y:  ", time()) . $data;
            else
                $line = $data;
        }
        fwrite($fp, $line);
        fclose($fp);
    }
}

//============================================================================

$server = new nusoap_server();
$server->configureWSDL('EXU', 'EXU');
$server->wsdl->schemaTargetNamespace = 'EXU';
$server->register('notifySmsTransaction', array(
    'request_id' => 'xsd:string', 
    'game_code' => 'xsd:string', 
    'telco' => 'xsd:string', 
    'mobile' => 'xsd:string', 
    'price' => 'xsd:string', 
    'amount' => 'xsd:string', 
    'command_code' => 'xsd:string', 
    'sms_message' => 'xsd:string'), array('result' => 'xsd:string'), 'EXU');

$server->register('notifyMobileTransaction', array(
    'request_id' => 'xsd:string', 
    'game_code' => 'xsd:string', 
    'telco' => 'xsd:string', 
    'mobile' => 'xsd:string', 
    'price' => 'xsd:string', 
    'amount' => 'xsd:string', 
    'command_code' => 'xsd:string', 
    'refer_code' => 'xsd:string'), array('result' => 'xsd:string'), 'EXU');

$HTTP_RAW_POST_DATA = (isset($HTTP_RAW_POST_DATA)) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>