<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 2/26/2016
 * Time: 9:51 AM
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

?>
<div id="credit-card" class="item-payment">
    <div class="title-item"><?=Yii::t('payment', 'Payment by bank cards')?></div>
    <div class="mgT-20">
        <div class="method_napcard_inner">
            <?php
            $f = ActiveForm::begin([
                'id' => 'NLpayBank',
                'enableAjaxValidation' => false,
                'enableClientValidation' => true,
                'action' => ''
            ]);
            ?>
            <div class="bg-bank">
                <div class="w-50">
                    <div class="noti-pay">
                        <p class="mgB-10"><span class="color-red d-ib pdR-10 font-700 text-decor"><?=Yii::t('payment', 'Note')?>:</span></p>
                        <div class="clearfix">
                            <ul class="val-payment">
                                <li><span>500,000 VND</span>=<span>510 <?=Yii::t('payment', 'Keys')?></span></li>
                                <li><span>200,000 VND</span>=<span>205 <?=Yii::t('payment', 'Keys')?></span></li>
                                <li><span>100,000 VND</span>=<span>100 <?=Yii::t('payment', 'Keys')?></span></li>
                                <li><span>50,000 VND</span>=<span>46 <?=Yii::t('payment', 'Keys')?></span></li>
                            </ul>
                        </div>
                    </div>
                    <table class="mgB-15 w-100">
                        <tr>
                            <td class="w-35"><?=Yii::t('payment', 'Method')?>:</td>
                            <td>
                                <select id="total_amount" name="total_amount" class="field-check form-control">
                                    <option value="ATM_ONLINE">Online Payment with a Local Card</option>
                                    <option value="IB_ONLINE">Internet banking</option>
                                    <option value="ATM_OFFLINE">Offline ATM</option>
                                    <option value="NH_OFFLINE">Bank Branch</option>
                                    <option value="VISA">Master or Visa Card</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="w-35"><?=Yii::t('payment', 'Bank')?>:</td>
                            <td>
                                <div class="box-dropdown dropdown-common">
                                    <div class="val-selected style-click">
                                        <span class="selected" data-placeholder="Chọn ngân hàng">Chọn ngân hàng</span>
                                        <span class="arrowDownFillFull"></span>
                                    </div>
                                    <div class="item-dropdown hide-dropdown">
                                        <ul class="clearfix">
                                            <li><a href="#" data-value="BIDV"><span class="d-ib mgR-10 ver-c"><i class="BIDV"></i></span>Ngân hàng TMCP Đầu tư &amp; Phát triển Việt Nam</a></li>
                                            <li><a href="#" data-value="VCB"><span class="d-ib mgR-10 ver-c"><i class="VCB"></i></span>Ngân hàng TMCP Ngoại Thương Việt Nam</a></li>
                                            <li><a href="#" data-value="DAB"><span class="d-ib mgR-10 ver-c"><i class="DAB"></i></span>Ngân hàng Đông Á</a></li>
                                            <li><a href="#" data-value="TCB"><span class="d-ib mgR-10 ver-c"><i class="TCB"></i></span>Ngân hàng Kỹ Thương</a></li>
                                            <li><a href="#" data-value="MB"><span class="d-ib mgR-10 ver-c"><i class="MB"></i></span>Ngân hàng Quân Đội</a></li>
                                            <li><a href="#" data-value="VIB"><span class="d-ib mgR-10 ver-c"><i class="VIB"></i></span>Ngân hàng Quốc tế</a></li>
                                            <li><a href="#" data-value="ICB"><span class="d-ib mgR-10 ver-c"><i class="ICB"></i></span>Ngân hàng Công Thương Việt Nam</a></li>
                                            <li><a href="#" data-value="EXB"><span class="d-ib mgR-10 ver-c"><i class="EXB"></i></span>Ngân hàng Xuất Nhập Khẩu</a></li>
                                            <li><a href="#" data-value="ACB"><span class="d-ib mgR-10 ver-c"><i class="ACB"></i></span>Ngân hàng Á Châu</a></li>
                                            <li><a href="#" data-value="HDB"><span class="d-ib mgR-10 ver-c"><i class="HDB"></i></span>Ngân hàng Phát triển Nhà TPHCM</a></li>
                                            <li><a href="#" data-value="MSB"><span class="d-ib mgR-10 ver-c"><i class="MSB"></i></span>Ngân hàng Hàng Hải</a></li>
                                            <li><a href="#" data-value="NVB"><span class="d-ib mgR-10 ver-c"><i class="NVB"></i></span>Ngân hàng Nam Việt</a></li>
                                            <li><a href="#" data-value="VAB"><span class="d-ib mgR-10 ver-c"><i class="VAB"></i></span>Ngân hàng Việt Á</a></li>
                                            <li><a href="#" data-value="VPB"><span class="d-ib mgR-10 ver-c"><i class="VPB"></i></span>Ngân Hàng Việt Nam Thịnh Vượng</a></li>
                                            <li><a href="#" data-value="SCB"><span class="d-ib mgR-10 ver-c"><i class="SCB"></i></span>Ngân hàng Sài Gòn Thương tín</a></li>
                                            <li><a href="#" data-value="PGB"><span class="d-ib mgR-10 ver-c"><i class="PGB"></i></span>Ngân hàng Xăng dầu Petrolimex</a></li>
                                            <li><a href="#" data-value="GPB"><span class="d-ib mgR-10 ver-c"><i class="GPB"></i></span>Ngân hàng TMCP Dầu khí Toàn Cầu</a></li>
                                            <li><a href="#" data-value="AGB"><span class="d-ib mgR-10 ver-c"><i class="AGB"></i></span>Ngân hàng Nông nghiệp &amp; Phát triển nông thôn</a></li>
                                            <li><a href="#" data-value="SGB"><span class="d-ib mgR-10 ver-c"><i class="SGB"></i></span>Ngân hàng Sài Gòn Công Thương</a></li>
                                            <li><a href="#" data-value="BAB"><span class="d-ib mgR-10 ver-c"><i class="BAB"></i></span>Ngân hàng Bắc Á</a></li>
                                            <li><a href="#" data-value="TPB"><span class="d-ib mgR-10 ver-c"><i class="TPB"></i></span>Tền phong bank</a></li>
                                            <li><a href="#" data-value="NAB"><span class="d-ib mgR-10 ver-c"><i class="NAB"></i></span>Ngân hàng Nam Á</a></li>
                                            <li><a href="#" data-value="SHB"><span class="d-ib mgR-10 ver-c"><i class="SHB"></i></span>Ngân hàng TMCP Sài Gòn - Hà Nội (SHB)</a></li>
                                            <li><a href="#" data-value="OJB"><span class="d-ib mgR-10 ver-c"><i class="OJB"></i></span>Ngân hàng TMCP Đại Dương (OceanBank)</a></li>
                                        </ul>
                                    </div>
                                    <input type="hidden" id="" value="">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="w-35"><?=Yii::t('payment', 'Amount')?>:</td>
                            <td>
                                <select id="total_amount" name="total_amount" class="field-check form-control">
                                    <option value="2000">2,000</option>
                                    <option value="500000">500,000</option>
                                    <option value="200000">200,000</option>
                                    <option value="100000">100,000</option>
                                    <option value="50000">50,000</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?=Yii::t('payment', 'Full name')?>:</td>
                            <td>
                                <input type="text" id="fullname" name="buyer_fullname" class="field-check form-control" value="<?=$profile->getDisplayName()?>">
                            </td>
                        </tr>
                        <tr>
                            <td><?=Yii::t('payment', 'Email')?>:</td>
                            <td>
                                <input type="text" id="fullname" name="buyer_email" class="field-check form-control" value="<?=$profile->public_email?>">
                            </td>
                        </tr>
                        <tr>
                            <td><?=Yii::t('payment', 'Phone number')?>:</td>
                            <td>
                                <input type="text" id="fullname" name="buyer_mobile" class="field-check form-control" value="<?=$profile->mobile?>">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <ul class="list-content">
                <li>
                    <label><input type="radio" value="ATM_ONLINE" name="option_payment"><?=Yii::t('payment', 'Online Payment with a Local Card')?></label>

                    <div class="boxContent">
                        <p class="mgB-10"><span class="color-red d-ib pdR-10 font-700 text-decor"><?=Yii::t('payment', 'Note')?>:</span> <?=Yii::t('payment', 'You need registered for internet banking prior to this transfer.')?></p>

                        <ul class="cardList clearfix">
                            <li class="bank-online-methods ">
                                <label for="bidv_ck_on">
                                    <i class="BIDV" title="Ngân hàng TMCP Đầu tư &amp; Phát triển Việt Nam"></i>
                                    <input type="radio" value="BIDV" name="bankcode" id="bidv_ck_on">

                                </label></li>
                            <li class="bank-online-methods ">
                                <label for="vcb_ck_on">
                                    <i class="VCB" title="Ngân hàng TMCP Ngoại Thương Việt Nam"></i>
                                    <input type="radio" value="VCB" name="bankcode" id="vcb_ck_on">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="dab_ck_on">
                                    <i class="DAB" title="Ngân hàng Đông Á"></i>
                                    <input type="radio" value="DAB" name="bankcode" id="dab_ck_on">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="tcb_ck_on">
                                    <i class="TCB" title="Ngân hàng Kỹ Thương"></i>
                                    <input type="radio" value="TCB" name="bankcode" id="tcb_ck_on">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="mb_ck_on">
                                    <i class="MB" title="Ngân hàng Quân Đội"></i>
                                    <input type="radio" value="MB" name="bankcode" id="mb_ck_on">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="vib_ck_on">
                                    <i class="VIB" title="Ngân hàng Quốc tế"></i>
                                    <input type="radio" value="VIB" name="bankcode" id="vib_ck_on">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="icb_ck_on">
                                    <i class="ICB" title="Ngân hàng Công Thương Việt Nam"></i>
                                    <input type="radio" value="ICB" name="bankcode" id="icb_ck_on">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="exb_ck_on">
                                    <i class="EXB" title="Ngân hàng Xuất Nhập Khẩu"></i>
                                    <input type="radio" value="EXB" name="bankcode" id="exb_ck_on">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="acb_ck_on">
                                    <i class="ACB" title="Ngân hàng Á Châu"></i>
                                    <input type="radio" value="ACB" name="bankcode" id="acb_ck_on">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="hdb_ck_on">
                                    <i class="HDB" title="Ngân hàng Phát triển Nhà TPHCM"></i>
                                    <input type="radio" value="HDB" name="bankcode" id="hdb_ck_on">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="msb_ck_on">
                                    <i class="MSB" title="Ngân hàng Hàng Hải"></i>
                                    <input type="radio" value="MSB" name="bankcode" id="msb_ck_on">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="nvb_ck_on">
                                    <i class="NVB" title="Ngân hàng Nam Việt"></i>
                                    <input type="radio" value="NVB" name="bankcode" id="nvb_ck_on">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="vab_ck_on">
                                    <i class="VAB" title="Ngân hàng Việt Á"></i>
                                    <input type="radio" value="VAB" name="bankcode" id="vab_ck_on">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="vpb_ck_on">
                                    <i class="VPB" title="Ngân Hàng Việt Nam Thịnh Vượng"></i>
                                    <input type="radio" value="VPB" name="bankcode" id="vpb_ck_on">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="scb_ck_on">
                                    <i class="SCB" title="Ngân hàng Sài Gòn Thương tín"></i>
                                    <input type="radio" value="SCB" name="bankcode" id="scb_ck_on">

                                </label></li>


                            <li class="bank-online-methods ">
                                <label for="pgb_ck_on">
                                    <i class="PGB" title="Ngân hàng Xăng dầu Petrolimex"></i>
                                    <input type="radio" value="PGB" name="bankcode" id="pgb_ck_on">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="gpb_ck_on">
                                    <i class="GPB" title="Ngân hàng TMCP Dầu khí Toàn Cầu"></i>
                                    <input type="radio" value="GPB" name="bankcode" id="gpb_ck_on">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="agb_ck_on">
                                    <i class="AGB" title="Ngân hàng Nông nghiệp &amp; Phát triển nông thôn"></i>
                                    <input type="radio" value="AGB" name="bankcode" id="agb_ck_on">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="sgb_ck_on">
                                    <i class="SGB" title="Ngân hàng Sài Gòn Công Thương"></i>
                                    <input type="radio" value="SGB" name="bankcode" id="sgb_ck_on">

                                </label></li>
                            <li class="bank-online-methods ">
                                <label for="bab_ck_on">
                                    <i class="BAB" title="Ngân hàng Bắc Á"></i>
                                    <input type="radio" value="BAB" name="bankcode" id="bab_ck_on">

                                </label></li>
                            <li class="bank-online-methods ">
                                <label for="tpb_ck_on">
                                    <i class="TPB" title="Tền phong bank"></i>
                                    <input type="radio" value="TPB" name="bankcode" id="tpb_ck_on">

                                </label></li>
                            <li class="bank-online-methods ">
                                <label for="nab_ck_on">
                                    <i class="NAB" title="Ngân hàng Nam Á"></i>
                                    <input type="radio" value="NAB" name="bankcode" id="nab_ck_on">

                                </label></li>
                            <li class="bank-online-methods ">
                                <label for="shb_ck_on">
                                    <i class="SHB" title="Ngân hàng TMCP Sài Gòn - Hà Nội (SHB)"></i>
                                    <input type="radio" value="SHB" name="bankcode" id="shb_ck_on">

                                </label></li>
                            <li class="bank-online-methods ">
                                <label for="ojb_ck_on">
                                    <i class="OJB" title="Ngân hàng TMCP Đại Dương (OceanBank)"></i>
                                    <input type="radio" value="OJB" name="bankcode" id="ojb_ck_on">

                                </label></li>

                        </ul>

                    </div>
                </li>
                <li>
                    <label><input type="radio" value="IB_ONLINE" name="option_payment"><?=Yii::t('payment', 'Internet banking')?></label>

                    <div class="boxContent">
                        <p class="mgB-10"><span class="color-red d-ib pdR-10 font-700 text-decor"><?=Yii::t('payment', 'Note')?>:</span> <?=Yii::t('payment', 'You need registered for internet banking prior to this transfer.')?></p>

                        <ul class="cardList clearfix">
                            <li class="bank-online-methods ">
                                <label for="bidv_ib">
                                    <i class="BIDV" title="Ngân hàng TMCP Đầu tư &amp; Phát triển Việt Nam"></i>
                                    <input type="radio" value="BIDV" name="bankcode" id="bidv_ib">

                                </label></li>
                            <li class="bank-online-methods ">
                                <label for="vcb_ib">
                                    <i class="VCB" title="Ngân hàng TMCP Ngoại Thương Việt Nam"></i>
                                    <input type="radio" value="VCB" name="bankcode" id="vcb_ib">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="dab_ib">
                                    <i class="DAB" title="Ngân hàng Đông Á"></i>
                                    <input type="radio" value="DAB" name="bankcode" id="dab_ib">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="tcb_ib">
                                    <i class="TCB" title="Ngân hàng Kỹ Thương"></i>
                                    <input type="radio" value="TCB" name="bankcode" id="tcb_ib">

                                </label></li>
                        </ul>

                    </div>
                </li>
                <li>
                    <label><input type="radio" value="ATM_OFFLINE" name="option_payment"><?=Yii::t('payment', 'Offline ATM')?></label>

                    <div class="boxContent">

                        <ul class="cardList clearfix">
                            <li class="bank-online-methods ">
                                <label for="bidv_off">
                                    <i class="BIDV" title="Ngân hàng TMCP Đầu tư &amp; Phát triển Việt Nam"></i>
                                    <input type="radio" value="BIDV" name="bankcode" id="bidv_off">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="vcb_off">
                                    <i class="VCB" title="Ngân hàng TMCP Ngoại Thương Việt Nam"></i>
                                    <input type="radio" value="VCB" name="bankcode" id="vcb_off">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="dab_off">
                                    <i class="DAB" title="Ngân hàng Đông Á"></i>
                                    <input type="radio" value="DAB" name="bankcode" id="dab_off">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="tcb_off">
                                    <i class="TCB" title="Ngân hàng Kỹ Thương"></i>
                                    <input type="radio" value="TCB" name="bankcode" id="tcb_off">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="mb_off">
                                    <i class="MB" title="Ngân hàng Quân Đội"></i>
                                    <input type="radio" value="MB" name="bankcode" id="mb_off">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="icb_ib">
                                    <i class="ICB" title="Ngân hàng Công Thương Việt Nam"></i>
                                    <input type="radio" value="ICB" name="bankcode" id="icb_ib">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="acb_ib">
                                    <i class="ACB" title="Ngân hàng Á Châu"></i>
                                    <input type="radio" value="ACB" name="bankcode" id="acb_ib">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="msb_ib">
                                    <i class="MSB" title="Ngân hàng Hàng Hải"></i>
                                    <input type="radio" value="MSB" name="bankcode" id="msb_ib">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="scb_off">
                                    <i class="SCB" title="Ngân hàng Sài Gòn Thương tín"></i>
                                    <input type="radio" value="SCB" name="bankcode" id="scb_off">

                                </label></li>
                            <li class="bank-online-methods ">
                                <label for="pgb_ib">
                                    <i class="PGB" title="Ngân hàng Xăng dầu Petrolimex"></i>
                                    <input type="radio" value="PGB" name="bankcode" id="pgb_ib">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="agb_ib">
                                    <i class="AGB" title="Ngân hàng Nông nghiệp &amp; Phát triển nông thôn"></i>
                                    <input type="radio" value="AGB" name="bankcode" id="agb_ib">

                                </label></li>
                            <li class="bank-online-methods ">
                                <label for="shb_off">
                                    <i class="SHB" title="Ngân hàng TMCP Sài Gòn - Hà Nội (SHB)"></i>
                                    <input type="radio" value="SHB" name="bankcode" id="shb_off">

                                </label></li>


                        </ul>

                    </div>
                </li>
                <li>
                    <label><input type="radio" value="NH_OFFLINE" name="option_payment"><?=Yii::t('payment', 'Bank Branch')?></label>

                    <div class="boxContent">

                        <ul class="cardList clearfix">
                            <li class="bank-online-methods ">
                                <label for="bidv_vp">
                                    <i class="BIDV" title="Ngân hàng TMCP Đầu tư &amp; Phát triển Việt Nam"></i>
                                    <input type="radio" value="BIDV" name="bankcode" id="bidv_vp">

                                </label></li>
                            <li class="bank-online-methods ">
                                <label for="vcb_vp">
                                    <i class="VCB" title="Ngân hàng TMCP Ngoại Thương Việt Nam"></i>
                                    <input type="radio" value="VCB" name="bankcode" id="vcb_vp">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="dab_vp">
                                    <i class="DAB" title="Ngân hàng Đông Á"></i>
                                    <input type="radio" value="DAB" name="bankcode" id="dab_vp">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="tcb_vp">
                                    <i class="TCB" title="Ngân hàng Kỹ Thương"></i>
                                    <input type="radio" value="TCB" name="bankcode" id="tcb_vp">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="mb_vp">
                                    <i class="MB" title="Ngân hàng Quân Đội"></i>
                                    <input type="radio" value="MB" name="bankcode" id="mb_vp">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="vib_vp">
                                    <i class="VIB" title="Ngân hàng Quốc tế"></i>
                                    <input type="radio" value="VIB" name="bankcode" id="vib_vp">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="icb_vp">
                                    <i class="ICB" title="Ngân hàng Công Thương Việt Nam"></i>
                                    <input type="radio" value="ICB" name="bankcode" id="icb_vp">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="acb_vp">
                                    <i class="ACB" title="Ngân hàng Á Châu"></i>
                                    <input type="radio" value="ACB" name="bankcode" id="acb_vp">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="msb_vp">
                                    <i class="MSB" title="Ngân hàng Hàng Hải"></i>
                                    <input type="radio" value="MSB" name="bankcode" id="msb_vp">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="scb_vp">
                                    <i class="SCB" title="Ngân hàng Sài Gòn Thương tín"></i>
                                    <input type="radio" value="SCB" name="bankcode" id="scb_vp">

                                </label></li>


                            <li class="bank-online-methods ">
                                <label for="pgb_vp">
                                    <i class="PGB" title="Ngân hàng Xăng dầu Petrolimex"></i>
                                    <input type="radio" value="PGB" name="bankcode" id="pgb_vp">

                                </label></li>

                            <li class="bank-online-methods ">
                                <label for="agb_vp">
                                    <i class="AGB" title="Ngân hàng Nông nghiệp &amp; Phát triển nông thôn"></i>
                                    <input type="radio" value="AGB" name="bankcode" id="agb_vp">

                                </label></li>
                            <li class="bank-online-methods ">
                                <label for="tpb_vp">
                                    <i class="TPB" title="Tền phong bank"></i>
                                    <input type="radio" value="TPB" name="bankcode" id="tpb_vp">

                                </label></li>


                        </ul>

                    </div>
                </li>
                <li>
                    <label><input type="radio" value="VISA" name="option_payment" selected="true"><?=Yii::t('payment', 'Master or Visa Card')?></label>

                    <div class="boxContent">
                        <p class="mgB-10"><span class="color-red d-ib pdR-10 font-700 text-decor"><?=Yii::t('payment', 'Note')?>:</span>Visa hoặc MasterCard.</p>
                        <ul class="cardList clearfix">
                            <li class="bank-online-methods ">
                                <label for="visa_vs">
                                    <input type="radio" value="VISA" name="bankcode" id="visa_vs">Visa
                                </label>
                            </li>
                            <li class="bank-online-methods ">
                                <label for="ms_vs">
                                    <input type="radio" value="MASTER" name="bankcode" id="ms_vs">Master
                                </label>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>

            <div class="mgT-20 text-left">
                <input type="submit" name="nlpayment" value="<?=Yii::t('payment', 'Submit');?>" class="btn-common btn-bd-radius"/>
            </div>
            
            <?php 
                $f->end(); 
                $this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/swiper.jquery.min.js', ['position' => View::POS_END]);
            ?>

            
            <script language="javascript">
                $(document).ready(function () {
                    
                    $('.bg-bank .dropdown-common').dropdown({
                        txtAdd: true,
                        styleShow: 0
                    });

                    $('input[name="option_payment"]').bind('click', function () {
                        $('.list-content li').removeClass('active');
                        $(this).parent().parent('li').addClass('active');
                    });

                    $('input[name="option_payment"]:first').trigger('click');
                    $('input[name="bankcode"]:first').trigger('click');

                    $('input[name="nlpayment"]').bind('click', function () {
                        var chkIsNull = false;
                        $('#NLpayBank input[type=text]').each(function(){
                            if($(this).val().trim() == ''){
                                chkIsNull = true;
                            }
                            console.log($(this));
                        })
                        if(chkIsNull==true){
                            alert(lajax.t('Please fill full data'));
                            return false;
                        }
                    });
                });
            </script>
        </div>
    </div>
</div>