<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 6/16/2016
 * Time: 11:33 AM
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
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
        <p class="mgB-10"><span class="color-red d-ib pdR-10 font-700 text-decor">Lưu ý:</span>
            <ul>
                <li>500,000vnd = 400 keys</li>
                <li>200,000vnd = 160 keys</li>
                <li>100,000nvd = 80 keys</li>
                <li>50,000vnd = 40 keys</li>
            </ul>
        </p>
        <table align="center">
            <tr>
                <td colspan="3" class="pdB-10">
                    <table>
                        <tr>
                            <td style="padding-left:0px;padding-top:5px" align="right" ><label for="92"><img  src="/images/nganluong/mobifone.jpg" /></label> </td>
                            <td style="padding-left:10px;padding-top:5px"><label for="93"><img  src="/images/nganluong/vinaphone.jpg" /></label></td>
                            <td style="padding-top:5px;padding-left:5px" align="left"><label for="107"><img  src="/images/nganluong/viettel.jpg" width="110" height="35" /></label></td>
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
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>Họ Tên:</td>
                <td>
                    <input type="text" id="fullname" name="buyer_fullname" class="field-check form-control" value="<?=$profile->getDisplayName()?>">
                </td>
            </tr>
            <tr>
                <td>Email:</td>
                <td>
                    <input type="text" id="fullname" name="buyer_email" class="field-check form-control" value="<?=$profile->public_email?>">
                </td>
            </tr>
            <tr>
                <td>Số Điện thoại:</td>
                <td>
                    <input type="text" id="fullname" name="buyer_mobile" class="field-check form-control" value="<?=$profile->mobile?>">
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