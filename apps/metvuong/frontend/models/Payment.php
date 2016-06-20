<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 6/16/2016
 * Time: 4:47 PM
 */

namespace frontend\models;

use Yii;
use yii\base\Component;


class Payment extends Component
{
    public function init()
    {
        if(!defined('URL_API')){
            define('URL_API', Yii::$app->params['nganluong']['URL_API']);
        } // Đường dẫn gọi api
        if(!defined('RECEIVER')){
            define('RECEIVER', Yii::$app->params['nganluong']['RECEIVER']);
        } // Email tài khoản ngân lượng
        if(!defined('MERCHANT_ID')){
            define('MERCHANT_ID', Yii::$app->params['nganluong']['MERCHANT_ID']);
        } // Mã merchant kết nối
        if(!defined('MERCHANT_PASS')){
            define('MERCHANT_PASS', Yii::$app->params['nganluong']['MERCHANT_PASS']);
        } // Mật khẩu kết nôi
        return parent::init();
    }

    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public function payByBank(){
        if(@$_POST['nlpayment']) {
            include(Yii::getAlias('@common/myvendor/nganluong/bank/includes/NL_Checkoutv3.php'));
            $nlcheckout= new \NL_CheckOutV3(MERCHANT_ID,MERCHANT_PASS,RECEIVER,URL_API);
            $total_amount=$_POST['total_amount'];

            $array_items[0]= array('item_name1' => 'Product name',
                'item_quantity1' => 1,
                'item_amount1' => $total_amount,
                'item_url1' => 'http://nganluong.vn/');

            $array_items=array();
            $payment_method =$_POST['option_payment'];
            $bank_code = @$_POST['bankcode'];
            $order_code ="macode_".time();

            $payment_type ='';
            $discount_amount =0;
            $order_description='';
            $tax_amount=0;
            $fee_shipping=0;
            $return_url ='http://localhost/nganluong.vn/checkoutv3/payment_success.php';
            $cancel_url =urlencode('http://localhost/nganluong.vn/checkoutv3?orderid='.$order_code) ;

            $buyer_fullname =$_POST['buyer_fullname'];
            $buyer_email =$_POST['buyer_email'];
            $buyer_mobile =$_POST['buyer_mobile'];

            $buyer_address ='';

            if($payment_method !='' && $buyer_email !="" && $buyer_mobile !="" && $buyer_fullname !="" && filter_var( $buyer_email, FILTER_VALIDATE_EMAIL )  ){
                if($payment_method =="VISA"){

                    $nl_result= $nlcheckout->VisaCheckout($order_code,$total_amount,$payment_type,$order_description,$tax_amount,
                        $fee_shipping,$discount_amount,$return_url,$cancel_url,$buyer_fullname,$buyer_email,$buyer_mobile,
                        $buyer_address,$array_items,$bank_code);

                }elseif($payment_method =="NL"){
                    $nl_result= $nlcheckout->NLCheckout($order_code,$total_amount,$payment_type,$order_description,$tax_amount,
                        $fee_shipping,$discount_amount,$return_url,$cancel_url,$buyer_fullname,$buyer_email,$buyer_mobile,
                        $buyer_address,$array_items);

                }elseif($payment_method =="ATM_ONLINE" && $bank_code !='' ){
                    $nl_result= $nlcheckout->BankCheckout($order_code,$total_amount,$bank_code,$payment_type,$order_description,$tax_amount,
                        $fee_shipping,$discount_amount,$return_url,$cancel_url,$buyer_fullname,$buyer_email,$buyer_mobile,
                        $buyer_address,$array_items) ;
                }
                elseif($payment_method =="NH_OFFLINE"){
                    $nl_result= $nlcheckout->officeBankCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items);
                }
                elseif($payment_method =="ATM_OFFLINE"){
                    $nl_result= $nlcheckout->BankOfflineCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items);

                }
                elseif($payment_method =="IB_ONLINE"){
                    $nl_result= $nlcheckout->IBCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items);
                }
            }
            //var_dump($nl_result); die;
            if ($nl_result->error_code =='00'){

                //Cập nhât order với token  $nl_result->token để sử dụng check hoàn thành sau này
                header('Location: '.(string)$nl_result->checkout_url);
                exit;
            }else{
                echo $nl_result->error_message;
            }

        }
    }

    public function payByMobiCard(){
        if(isset($_POST['NLNapThe'])){
            include(Yii::getAlias('@common/myvendor/nganluong/card/config.php'));
            include(Yii::getAlias('@common/myvendor/nganluong/card/includes/MobiCard.php'));

            $soseri = $_POST['txtSoSeri'];
            $sopin = $_POST['txtSoPin'];
            $type_card = $_POST['select_method'];


            if ($_POST['txtSoSeri'] == "" ) {
                echo '<script>alert("Vui lòng nhập Số Seri");</script>';
                echo "<script>location.href='".$_SERVER['HTTP_REFERER']."';</script>";
                exit();
            }
            if ($_POST['txtSoPin'] == "" ) {
                echo '<script>alert("Vui lòng nhập Mã Thẻ");</script>';
                echo "<script>location.href='".$_SERVER['HTTP_REFERER']."';</script>";
                exit();
            }

            $arytype= array(92=>'VMS',93=>'VNP',107=>'VIETTEL',121=>'VCOIN',120=>'GATE');
            //Tiến hành kết nối thanh toán Thẻ cào.
            $call = new \MobiCard();
            $rs = new \Result();
            $coin1 = rand(10,999);
            $coin2 = rand(0,999);
            $coin3 = rand(0,999);
            $coin4 = rand(0,999);
            $ref_code = $coin4 + $coin3 * 1000 + $coin2 * 1000000 + $coin1 * 100000000;

            $rs = $call->CardPay($sopin,$soseri,$type_card,$ref_code,"","","");

            if($rs->error_code == '00') {
                // Cập nhật data tại đây
                echo  '<script>alert("Bạn đã nạp thành công '.$rs->card_amount.' vào trong tài khoản.");</script>'; //$total_results;
            }
            else {
                echo  '<script>alert("Lỗi :'.$rs->error_message.'");</script>';
            }

            //var_dump($rs);

        }
    }
}