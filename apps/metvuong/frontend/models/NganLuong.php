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


class NganLuong extends Component
{
    const PRICE_LIST = [2000,50000,100000,200000,500000];
    const METHOD_BANKING = 1;
    const METHOD_MOBILE_CARD = 2;
    const METHOD_SMS = 3;
    protected $nlcheckout;

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
        if (!class_exists('NL_CheckOutV3')) {
            include(Yii::getAlias('@common/myvendor/nganluong/bank/includes/NL_Checkoutv3.php'));
        }
        if(empty($this->nlcheckout)){
            $this->nlcheckout= new \NL_CheckOutV3(MERCHANT_ID,MERCHANT_PASS,RECEIVER,URL_API);
        }
        return parent::init();
    }

    public static function me()
    {
        return Yii::createObject(self::className());
    }

    public function payByBank($data){
        if(@$_POST['nlpayment']) {
            $total_amount=$_POST['total_amount'];
            $array_items=array();
            $payment_method =$_POST['option_payment'];
            $bank_code = @$_POST['bankcode'];
            $order_code = $data['transaction_code'];

            $payment_type ='';
            $discount_amount =0;
            $order_description='';
            $tax_amount=0;
            $fee_shipping=0;
            $return_url = $data['return_url'];
            $cancel_url =urlencode($data['cancel_url']) ;

            $buyer_fullname =$_POST['buyer_fullname'];
            $buyer_email =$_POST['buyer_email'];
            $buyer_mobile =$_POST['buyer_mobile'];

            $buyer_address ='';

            if($payment_method !='' && $buyer_email !="" && $buyer_mobile !="" && $buyer_fullname !="" && filter_var( $buyer_email, FILTER_VALIDATE_EMAIL )  ){
                if($payment_method =="VISA"){

                    $nl_result= $this->nlcheckout->VisaCheckout($order_code,$total_amount,$payment_type,$order_description,$tax_amount,
                        $fee_shipping,$discount_amount,$return_url,$cancel_url,$buyer_fullname,$buyer_email,$buyer_mobile,
                        $buyer_address,$array_items,$bank_code);

                }elseif($payment_method =="NL"){
                    $nl_result= $this->nlcheckout->NLCheckout($order_code,$total_amount,$payment_type,$order_description,$tax_amount,
                        $fee_shipping,$discount_amount,$return_url,$cancel_url,$buyer_fullname,$buyer_email,$buyer_mobile,
                        $buyer_address,$array_items);

                }elseif($payment_method =="ATM_ONLINE" && $bank_code !='' ){
                    $nl_result= $this->nlcheckout->BankCheckout($order_code,$total_amount,$bank_code,$payment_type,$order_description,$tax_amount,
                        $fee_shipping,$discount_amount,$return_url,$cancel_url,$buyer_fullname,$buyer_email,$buyer_mobile,
                        $buyer_address,$array_items) ;
                }
                elseif($payment_method =="NH_OFFLINE"){
                    $nl_result= $this->nlcheckout->officeBankCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items);
                }
                elseif($payment_method =="ATM_OFFLINE"){
                    $nl_result= $this->nlcheckout->BankOfflineCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items);

                }
                elseif($payment_method =="IB_ONLINE"){
                    $nl_result= $this->nlcheckout->IBCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items);
                }
            }
            //var_dump($nl_result); die;
            if ($nl_result->error_code =='00'){
                //Cập nhât order với token  $nl_result->token để sử dụng check hoàn thành sau này
                Transaction::me()->saveTransaction($data['transaction_code'], [
                    'status'=>Transaction::STATUS_PROCESSING,
                ]);
                Payment::me()->transactionNganluong($nl_result->token, [
                    'transaction_code'=>$data['transaction_code'],
                    'payment_method'=>NganLuong::METHOD_BANKING,
                    'amount'=>$total_amount,
                    'buyer_fullname'=>$buyer_fullname,
                    'buyer_email'=>$buyer_email,
                    'buyer_mobile'=>$buyer_mobile,
                    'bankcode'=>$bank_code,
                    'option_payment'=>$payment_method,
                    'status'=>Transaction::STATUS_PENDING,
                ]);
                header('Location: '.(string)$nl_result->checkout_url);
                exit;
            }else{
                return ['error_code'=>$nl_result->error_code, 'error_message'=>$nl_result->error_message];
            }

        }
    }

    public function payByMobiCard($data){
        if(isset($_POST['NLNapThe'])){
            include(Yii::getAlias('@common/myvendor/nganluong/card/config.php'));
            include(Yii::getAlias('@common/myvendor/nganluong/card/includes/MobiCard.php'));
            $soseri = $_POST['txtSoSeri'];
            $sopin = $_POST['txtSoPin'];
            $type_card = $_POST['select_method'];
            if ($_POST['txtSoSeri'] == "" ) {
                $return = ['error_code'=>111, 'error_message'=>'Please input Seri'];
            }
            if ($_POST['txtSoPin'] == "" ) {
                $return = ['error_code'=>111, 'error_message'=>'Please input Code'];
            }
            //Tiến hành kết nối thanh toán Thẻ cào.
            $call = new \MobiCard();
            $rs = new \Result();
            $coin1 = rand(10,999);
            $coin2 = rand(0,999);
            $coin3 = rand(0,999);
            $coin4 = rand(0,999);
            $ref_code = $coin4 + $coin3 * 1000 + $coin2 * 1000000 + $coin1 * 100000000;
            $ref_code = $data['transaction_code'];

            $buyer_fullname =$_POST['buyer_fullname'];
            $buyer_email =$_POST['buyer_email'];
            $buyer_mobile =$_POST['buyer_mobile'];

            $rs = $call->CardPay($sopin,$soseri,$type_card,$ref_code,$buyer_fullname,$buyer_mobile,$buyer_email);
            if($rs->error_code == '00') {
                // Cập nhật data tại đây
                Payment::me()->processTransactionByMobileCard($data['transaction_code'], $rs);
                $return = ['error_code'=>0, 'error_message'=>'You have paid {card_amount} successfully into your account', 'card_amount'=>$rs->card_amount];
            }
            else {
                $return = ['error_code'=>$rs->error_code, 'error_message'=>$rs->error_message];
            }
            return $return;
        }
    }

    public function getTransactionDetail($token){
        return $this->nlcheckout->GetTransactionDetail($token);
    }

    public function VND2Keys($type = null, $price = null)
    {
        $arr = [
            self::METHOD_BANKING => [
                500000  => 510,
                200000  => 205,
                100000  => 100,
                50000   => 46,
                2000   => 1,
            ],
            self::METHOD_MOBILE_CARD => [
                500000  => 400,
                200000  => 160,
                100000  => 80,
                50000   => 40,
            ],
            self::METHOD_SMS => [
                100000  => 54,
                50000   => 27,
            ],
        ];
        if($type == self::METHOD_BANKING){
            $return = $arr[$type][$price];
        }elseif($type == self::METHOD_MOBILE_CARD){
            return $price*(80/100)/1000;
        }elseif($type == self::METHOD_MOBILE_CARD){
            $return = $arr[$type][$price];
        }else{
            $return = $arr;
        }
        return $return;
    }
}