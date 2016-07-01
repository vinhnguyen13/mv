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
                    <table class="mgB-15 w-100 payFrm">
                        <tr class="payMethod">
                            <td class="w-35"><?=Yii::t('payment', 'Method')?>:</td>
                            <td>
                                <div class="box-dropdown dropdown-common">
                                    <div class="val-selected style-click">
                                        <span class="selected" data-placeholder="Chọn phương thức thanh toán">Chọn phương thức thanh toán</span>
                                        <span class="arrowDownFillFull"></span>
                                    </div>
                                    <div class="item-dropdown hide-dropdown">
                                        <ul class="clearfix">
                                            <li><a href="#" data-value="ATM_ONLINE"><?=Yii::t('payment', 'Online Payment with a Local Card')?></a></li>
                                            <li><a href="#" data-value="IB_ONLINE"><?=Yii::t('payment', 'Internet banking')?></a></li>
                                            <li><a href="#" data-value="ATM_OFFLINE"><?=Yii::t('payment', 'Offline ATM')?></a></li>
                                            <li><a href="#" data-value="NH_OFFLINE"><?=Yii::t('payment', 'Bank Branch')?></a></li>
                                            <li><a href="#" data-value="VISA"><?=Yii::t('payment', 'Master or Visa Card')?></a></li>
                                        </ul>
                                    </div>
                                    <input type="hidden" name="option_payment" id="" value="">
                                </div>
                            </td>
                        </tr>
                        <tr class="payBank">
                            <td class="w-35"><?=Yii::t('payment', 'Bank')?>:</td>
                            <td>
                                <div class="box-dropdown dropdown-common">
                                    <div class="val-selected style-click">
                                        <span class="selected" data-placeholder="Chọn ngân hàng">Chọn ngân hàng</span>
                                        <span class="arrowDownFillFull"></span>
                                    </div>
                                    <div class="item-dropdown hide-dropdown">
                                        <ul class="clearfix" data-group="ATM_ONLINE">
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
                                            <li><a href="#" data-value="VISA">VISA</a></li>
                                            <li><a href="#" data-value="MASTER">MASTER</a></li>
                                        </ul>
                                        <ul class="clearfix" data-group="IB_ONLINE" style="display:none;">
                                            <li><a href="#" data-value="BIDV"><span class="d-ib mgR-10 ver-c"><i class="BIDV"></i></span>Ngân hàng TMCP Đầu tư &amp; Phát triển Việt Nam</a></li>
                                            <li><a href="#" data-value="VCB"><span class="d-ib mgR-10 ver-c"><i class="VCB"></i></span>Ngân hàng TMCP Ngoại Thương Việt Nam</a></li>
                                            <li><a href="#" data-value="DAB"><span class="d-ib mgR-10 ver-c"><i class="DAB"></i></span>Ngân hàng Đông Á</a></li>
                                            <li><a href="#" data-value="TCB"><span class="d-ib mgR-10 ver-c"><i class="TCB"></i></span>Ngân hàng Kỹ Thương</a></li>
                                        </ul>
                                        <ul class="clearfix" data-group="ATM_OFFLINE" style="display:none;">
                                            <li><a href="#" data-value="BIDV"><span class="d-ib mgR-10 ver-c"><i class="BIDV"></i></span>Ngân hàng TMCP Đầu tư &amp; Phát triển Việt Nam</a></li>
                                            <li><a href="#" data-value="VCB"><span class="d-ib mgR-10 ver-c"><i class="VCB"></i></span>Ngân hàng TMCP Ngoại Thương Việt Nam</a></li>
                                            <li><a href="#" data-value="DAB"><span class="d-ib mgR-10 ver-c"><i class="DAB"></i></span>Ngân hàng Đông Á</a></li>
                                            <li><a href="#" data-value="TCB"><span class="d-ib mgR-10 ver-c"><i class="TCB"></i></span>Ngân hàng Kỹ Thương</a></li>
                                            <li><a href="#" data-value="MB"><span class="d-ib mgR-10 ver-c"><i class="MB"></i></span>Ngân hàng Quân Đội</a></li>
                                            <li><a href="#" data-value="ICB"><span class="d-ib mgR-10 ver-c"><i class="ICB"></i></span>Ngân hàng Công Thương Việt Nam</a></li>
                                            <li><a href="#" data-value="ACB"><span class="d-ib mgR-10 ver-c"><i class="ACB"></i></span>Ngân hàng Á Châu</a></li>
                                            <li><a href="#" data-value="MSB"><span class="d-ib mgR-10 ver-c"><i class="MSB"></i></span>Ngân hàng Hàng Hải</a></li>
                                            <li><a href="#" data-value="SCB"><span class="d-ib mgR-10 ver-c"><i class="SCB"></i></span>Ngân hàng Sài Gòn Thương tín</a></li>
                                            <li><a href="#" data-value="PGB"><span class="d-ib mgR-10 ver-c"><i class="PGB"></i></span>Ngân hàng Xăng dầu Petrolimex</a></li>
                                            <li><a href="#" data-value="AGB"><span class="d-ib mgR-10 ver-c"><i class="AGB"></i></span>Ngân hàng Nông nghiệp &amp; Phát triển nông thôn</a></li>
                                            <li><a href="#" data-value="SHB"><span class="d-ib mgR-10 ver-c"><i class="SHB"></i></span>Ngân hàng TMCP Sài Gòn - Hà Nội (SHB)</a></li>
                                        </ul>
                                        <ul class="clearfix" data-group="NH_OFFLINE" style="display:none;">
                                            <li><a href="#" data-value="BIDV"><span class="d-ib mgR-10 ver-c"><i class="BIDV"></i></span>Ngân hàng TMCP Đầu tư &amp; Phát triển Việt Nam</a></li>
                                            <li><a href="#" data-value="VCB"><span class="d-ib mgR-10 ver-c"><i class="VCB"></i></span>Ngân hàng TMCP Ngoại Thương Việt Nam</a></li>
                                            <li><a href="#" data-value="DAB"><span class="d-ib mgR-10 ver-c"><i class="DAB"></i></span>Ngân hàng Đông Á</a></li>
                                            <li><a href="#" data-value="TCB"><span class="d-ib mgR-10 ver-c"><i class="TCB"></i></span>Ngân hàng Kỹ Thương</a></li>
                                            <li><a href="#" data-value="MB"><span class="d-ib mgR-10 ver-c"><i class="MB"></i></span>Ngân hàng Quân Đội</a></li>
                                            <li><a href="#" data-value="VIB"><span class="d-ib mgR-10 ver-c"><i class="VIB"></i></span>Ngân hàng Quốc tế</a></li>
                                            <li><a href="#" data-value="ICB"><span class="d-ib mgR-10 ver-c"><i class="ICB"></i></span>Ngân hàng Công Thương Việt Nam</a></li>
                                            <li><a href="#" data-value="ACB"><span class="d-ib mgR-10 ver-c"><i class="ACB"></i></span>Ngân hàng Á Châu</a></li>
                                            <li><a href="#" data-value="MSB"><span class="d-ib mgR-10 ver-c"><i class="MSB"></i></span>Ngân hàng Hàng Hải</a></li>
                                            <li><a href="#" data-value="SCB"><span class="d-ib mgR-10 ver-c"><i class="SCB"></i></span>Ngân hàng Sài Gòn Thương tín</a></li>
                                            <li><a href="#" data-value="PGB"><span class="d-ib mgR-10 ver-c"><i class="PGB"></i></span>Ngân hàng Xăng dầu Petrolimex</a></li>
                                            <li><a href="#" data-value="AGB"><span class="d-ib mgR-10 ver-c"><i class="AGB"></i></span>Ngân hàng Nông nghiệp &amp; Phát triển nông thôn</a></li>
                                            <li><a href="#" data-value="TPB"><span class="d-ib mgR-10 ver-c"><i class="TPB"></i></span>Tền phong bank</a></li>
                                        </ul>
                                        <ul class="clearfix" data-group="VISA" style="display:none;">
                                            <li><a href="#" data-value="VISA">VISA</a></li>
                                            <li><a href="#" data-value="MASTER">MASTER</a></li>
                                        </ul>
                                    </div>
                                    <input type="hidden" name="bankcode" id="" value="">
                                </div>
                            </td>
                        </tr>
                        <tr class="payAmount">
                            <td class="w-35"><?=Yii::t('payment', 'Amount')?>:</td>
                            <td>
                                <div class="box-dropdown dropdown-common">
                                    <div class="val-selected style-click">
                                        <span class="selected" data-placeholder="Chọn số tiền">Chọn số tiền</span>
                                        <span class="arrowDownFillFull"></span>
                                    </div>
                                    <div class="item-dropdown hide-dropdown">
                                        <ul class="clearfix">
                                            <?php
                                            $env = YII_ENV;
                                            if(!empty($env) && in_array($env, [YII_ENV_DEV])) {
                                                ?>
                                                <li><a href="#" data-value="2000">2,000</a></li>
                                                <?php
                                            }
                                            ?>
                                            <li><a href="#" data-value="500000">500,000</a></li>
                                            <li><a href="#" data-value="200000">200,000</a></li>
                                            <li><a href="#" data-value="100000">100,000</a></li>
                                            <li><a href="#" data-value="50000">50,000</a></li>
                                        </ul>
                                    </div>
                                    <input type="hidden" name="total_amount" id="" value="">
                                </div>
                            </td>
                        </tr>
                        <tr class="payFullname">
                            <td><?=Yii::t('payment', 'Full name')?>:</td>
                            <td>
                                <input type="text" id="fullname" name="buyer_fullname" class="field-check form-control" value="<?=$profile->getDisplayName()?>">
                            </td>
                        </tr>
                        <tr class="payEmail">
                            <td><?=Yii::t('payment', 'Email')?>:</td>
                            <td>
                                <input type="text" id="fullname" name="buyer_email" class="field-check form-control" value="<?=$profile->public_email?>">
                            </td>
                        </tr>
                        <tr class="payPhone">
                            <td><?=Yii::t('payment', 'Phone number')?>:</td>
                            <td>
                                <input type="text" id="fullname" name="buyer_mobile" class="field-check form-control" value="<?=$profile->mobile?>">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
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
                        styleShow: 0,
                        funCallBack: function(item) {
                            var indx = $(item).closest('tr').index();
                            var clss = $(item).closest('tr').attr('class');
                            if(clss == 'payMethod'){
                                var payMethodGroup = $(item).attr('data-value');
                                $('.payBank ul').hide();
                                $('.payBank ul[data-group="'+payMethodGroup+'"]').show();
                            }
                            /*if(indx == 2){
                                $('.payFrm tr').show();

                            }else if($('.payFrm tr:eq('+(indx+1)+')')){
                                $('.payFrm tr:eq('+(indx+1)+')').show();
                            }*/
                        }
                    });

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