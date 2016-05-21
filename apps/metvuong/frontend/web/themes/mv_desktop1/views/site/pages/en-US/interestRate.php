<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 4/13/2016
 * Time: 9:52 AM
 */

Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/tools.js', ['position' => \yii\web\View::POS_BEGIN]);
?>
<script type="text/javascript">var base_url = '<?=\yii\helpers\Url::home()?>';</script>

<div class="title-fixed-wrap container">
    <div class="tool-cacu">
        <div class="news_blocks_contain">
            <div class="title-top"> HOW TO CALCULATE THE INTEREST RATES? </div>
            <div class="sum"> MetVuong set up a system which allow you calculate the interest rates from bank, therefore you can easily to make your current plan. </div>
            <table class="funding-tbl">
                <tbody id="thongTinVayVon">
                <tr>
                    <td class="label"> Start date </td>
                    <td>
                        <?=\yii\jui\DatePicker::widget([
                            'language' => 'en',
                            'dateFormat' => 'yyyy-MM-dd',
                            'options' => [
                                // you can hide the input by setting the following
                                 'id' => 'ngayBatDau',
                            ]
                        ]);
                        ?>

                    </td>
                </tr>
                <tr>
                    <td class="label"> Loan </td>
                    <td>
                        <input type="text" id="soTienVay" name="soTienVay" value="">
                        VND
                    </td>
                </tr>
                <tr>
                    <td class="label"> Form of payment </td>
                    <td>
                        <select id="hinhThucTraNo" name="hinhThucTraNo">
                            <option value=""> Choose options </option>
                            <option value="4"> Pays principal monthly, pay interest rate with reducing balance Pay </option>
                            <option value="3">Pay constantly yearly</option>
                            <option value="2"> Pay principal at the last period, pay interest rate monthly with actual reducing balance. </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="label"> Interest rates </td>
                    <td>
                        <input type="text" id="laiSuat" name="laiSuat" value="">
                        <select id="theoThoiGian" name="theoThoiGian">
                            <option value="thang"> Monthly </option>
                            <option value="nam"> Yearly </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="label"> Time Duration </td>
                    <td> <input type="text" name="thoiGianVay" id="thoiGianVay" value=""> Months </td>
                </tr>
                <tr>
                    <td class="label"> </td>
                    <td> <a href="#" onclick="Tools.loan_calculate();" class="btn-form btn-common"> Calculate <span class="arrow-icon"> </span> </a> </td>
                </tr>
                </tbody>
            </table>
            <div class="tool-hdr black-hdr"> Result </div>
            <article>
                <table class="funding-tbl">
                    <tbody>
                    <tr>
                        <td class="label"> Start date of loan </td>
                        <td id="inNgayBatDauVay"> </td>
                    </tr>
                    <tr>
                        <td class="label"> Loan Amount: </td>
                        <td id="inSoTienVay"> </td>
                    </tr>
                    <tr>
                        <td class="label"> Form of payment: </td>
                        <td id="inHinhThucTraNo"> </td>
                    </tr>
                    <tr>
                        <td class="label"> Interest rate: </td>
                        <td id="inLaiXuatThucPhaiTra"> </td>
                    </tr>
                    <tr>
                        <td class="label"> Time duration: </td>
                        <td id="inThoiGianVay"> </td>
                    </tr>
                    <tr>
                        <td class="label"> Amount need to repayment: </td>
                        <td id="inTongSoTienPhaiTra"> </td>
                    </tr>
                    </tbody>
                </table>
            </article>
            <article id="inKetQua"></article>

            <div id="kn_1" style="display: none;"></div>
            <div id="kn_2" style="display: none;"></div>
            <div id="kn_3" style="display: none;"></div>
            <div id="kn_4" style="display: none;"></div>

        </div>
    </div>
</div>