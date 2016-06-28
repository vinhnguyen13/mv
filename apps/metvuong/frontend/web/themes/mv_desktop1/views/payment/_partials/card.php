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
    <div class="title-item"><?=Yii::t('payment','Mobile Credit')?></div>
    <div class="pdT-20">
        <div class="noti-pay w-50">
            <p class="mgB-10"><span class="color-red d-ib pdR-10 font-700 text-decor"><?=Yii::t('payment', 'Note')?>:</span>
                <?=Yii::t('payment', 'The amount of keys added to your account will be after a <b>10%</b> service charge and <b>10%</b> VAT on top. E.g. Buying 100.000 VND through Mobile Credit will add 81.000 VND worth of Keys to your account.')?>
            </p>
            <div class="clearfix">
                <ul class="val-payment">
                    <li><span>500,000 VND</span>=<span>400 <?=Yii::t('payment', 'Keys')?></span></li>
                    <li><span>200,000 VND</span>=<span>160 <?=Yii::t('payment', 'Keys')?></span></li>
                    <li><span>100,000 VND</span>=<span>80 <?=Yii::t('payment', 'Keys')?></span></li>
                    <li><span>50,000 VND</span>=<span>40 <?=Yii::t('payment', 'Keys')?></span></li>
                </ul>
            </div>
        </div>

        <table align="center" class="w-50">
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
                <td><?=Yii::t('payment', 'Full name')?>:</td>
                <td>
                    <input type="text" id="fullname" name="buyer_fullname" class="field-check form-control" value="<?=$profile->getDisplayName()?>" readonly="readonly">
                </td>
            </tr>
            <tr>
                <td><?=Yii::t('payment', 'Email')?>:</td>
                <td>
                    <input type="text" id="fullname" name="buyer_email" class="field-check form-control" value="<?=$profile->public_email?>" readonly="readonly">
                </td>
            </tr>
            <tr>
                <td><?=Yii::t('payment', 'Phone number')?>:</td>
                <td>
                    <input type="text" id="fullname" name="buyer_mobile" class="field-check form-control" value="<?=$profile->mobile?>" readonly="readonly">
                </td>
            </tr>
            <tr>
                <td><?=Yii::t('payment', 'Seri')?>:</td>
                <td colspan="2"><input type="text" id="txtSoSeri" name="txtSoSeri" class="form-control" /></td>
            </tr>
            <tr>
                <td><?=Yii::t('payment', 'Card number')?>: </td>
                <td colspan="2">
                    <input type="text" id="txtSoPin" name="txtSoPin" class="form-control" />

                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" id="" class="btn-common btn-bd-radius" name="NLNapThe" value="<?=Yii::t('payment', 'Submit')?>"  />
                </td>
            </tr>
        </table>
    </div>
</div>
<?php $f->end(); ?>