<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 2/26/2016
 * Time: 9:51 AM
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>
<div id="credit-card" class="item-payment">
    <div class="title-item">Thanh toán bằng Thẻ Ngân Hàng</div>
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

            <div class="w-50">
                <div class="noti-pay">
                    <p class="mgB-10"><span class="color-red d-ib pdR-10 font-700 text-decor">Lưu ý:</span></p>
                    <div class="clearfix">
                        <ul class="val-payment">
                            <li>
                                <span>500,000vnd</span>
                                =
                                <span>510 keys</span>
                            </li>
                            <li>
                                <span>200,000vnd</span>
                                =
                                <span>205 keys</span>
                            </li>
                            <li>
                                <span>100,000vnd</span>
                                =
                                <span>100 keys</span>
                            </li>
                            <li>
                                <span>50,000vnd</span>
                                =
                                <span>46 keys</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <table class="mgB-15 w-100">
                    <tr>
                        <td class="w-35">Số tiền thanh toán:</td>
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
                </table>
            </div>
            <ul class="list-content">
                <li>
                    <label><input type="radio" value="ATM_ONLINE" name="option_payment">Thanh toán online bằng THẺ NGÂN HÀNG NỘI ĐỊA</label>

                    <div class="boxContent">
                        <p class="mgB-10"><span class="color-red d-ib pdR-10 font-700 text-decor">Lưu ý:</span> Bạn cần đăng ký Internet-Banking hoặc dịch vụ thanh toán trực tuyến tại ngân hàng trước khi thực hiện.</p>

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
                    <label><input type="radio" value="IB_ONLINE" name="option_payment">Thanh toán bằng INTERNET BANKING</label>

                    <div class="boxContent">
                        <p class="mgB-10"><span class="color-red d-ib pdR-10 font-700 text-decor">Lưu ý:</span> Bạn cần đăng ký Internet-Banking hoặc dịch vụ thanh toán trực tuyến tại ngân hàng trước khi thực hiện.</p>

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
                    <label><input type="radio" value="ATM_OFFLINE" name="option_payment">Thanh toán ATM OFFLINE</label>

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
                    <label><input type="radio" value="NH_OFFLINE" name="option_payment">Thanh toán tại VĂN PHÒNG NGÂN HÀNG</label>

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
                    <label><input type="radio" value="VISA" name="option_payment" selected="true">Thanh toán bằng THẺ VISA HOẶC MASTERCARD</label>

                    <div class="boxContent">
                        <p class="mgB-10"><span class="color-red d-ib pdR-10 font-700 text-decor">Lưu ý:</span>Visa hoặc MasterCard.</p>
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
                <input type="submit" name="nlpayment" value="thanh toán" class="btn-common btn-bd-radius"/>
            </div>
            
            <?php $f->end(); ?>

            <script language="javascript">
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
            </script>
        </div>
    </div>
</div>