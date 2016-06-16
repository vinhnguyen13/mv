<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 6/16/2016
 * Time: 11:33 AM
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;


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
    $call = new MobiCard();
    $rs = new Result();
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
?>
<?php
$f = ActiveForm::begin([
    'id' => 'napthe',
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'action' => ''
]);
?>
<div id="the-cao" class="item-payment">
    <div class="title-item">Thanh toán bằng thẻ cào điện thoại</div>
    <div class="pd-20">
        <table align="center">
            <tr>
                <td colspan="3" class="pdB-10">
                    <table>
                        <tr>
                            <td style="padding-left:0px;padding-top:5px" align="right" ><label for="92"><img  src="/images/nganluong/mobifone.jpg" /></label> </td>
                            <td style="padding-left:10px;padding-top:5px"><label for="93"><img  src="/images/nganluong/vinaphone.jpg" /></label></td>
                            <td style="padding-top:5px;padding-left:5px" align="left"><label for="107"><img  src="/images/nganluong/viettel.jpg" width="110" height="35" /></label></td>
                            <td style="padding-top:5px;padding-left:5px" align="left"><label for="121"><img width="100" height="35" src="/images/nganluong/vtc.jpg"></label> </td>
                            <td style="padding-top:5px;padding-left:5px" align="left"> <label for="120"><img width="100" height="35" src="/images/nganluong/gate.jpg"></label></td>
                        </tr>
                        <tr>
                            <td align="center" style="padding-bottom:0px;">
                                <input type="radio" name="select_method" checked="true" value="VMS" id="92"  />
                            </td>
                            <td align="center" style="padding-bottom:0px;padding-left:5px">
                                <input type="radio"  name="select_method" value="VNP" id="93" />
                            </td>
                            <td align="center" style="padding-bottom:0px;padding-right:0px">
                                <input type="radio"  name="select_method" value="VIETTEL" id="107" />
                            </td>
                            <td align="center" style="padding-right:10px">
                                <input type="radio" id="121" value="VCOIN" name="select_method">
                            </td>

                            <td align="center" style="padding-bottom:0px;padding-right:0px">
                                <input type="radio" id="120" value="GATE" name="select_method">
                            </td>

                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td>Số Seri :</td>
                <td colspan="2"><input type="text" id="txtSoSeri" name="txtSoSeri" class="form-control" /></td>
            </tr>
            <tr>
                <td>Mã số thẻ : </td>
                <td colspan="2">
                    <input type="text" id="txtSoPin" name="txtSoPin" class="form-control" />

                </td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <input type="submit" id="" class="btn-common btn-bd-radius" name="NLNapThe" value="Nạp Thẻ"  />
                </td>
            </tr>
        </table>
    </div>
</div>
<?php $f->end(); ?>