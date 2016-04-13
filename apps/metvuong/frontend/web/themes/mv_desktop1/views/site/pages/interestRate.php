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

<div class="title-fixed-wrap">
    <div class="tool-cacu">
        <div class="news_blocks_contain">
            <div class="tool-hdr"> Tính lãi suất vay vốn </div>
            <div class="sum"> Cho phép bạn tính toán tiền tiết kiệm khi gửi một số tiền theo một kỳ hạn nhất định và so sánh lãi suất, tiền được hưởng giữa các ngân hàng </div>                        
            <table class="funding-tbl">
                <tbody id="thongTinVayVon">
                <tr>
                    <td class="label"> Ngày bắt đầu </td>
                    <td>
                        <?
                        echo \yii\jui\DatePicker::widget([
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
                    <td class="label"> Số tiền vay </td>
                    <td>
                        <input type="text" id="soTienVay" name="soTienVay" value="">
                        VND
                    </td>
                </tr>
                <tr>
                    <td class="label"> Hình thức trả nợ </td>
                    <td>
                        <select id="hinhThucTraNo" name="hinhThucTraNo">
                            <option value=""> Chọn hình thức trả nợ </option>
                            <option value="4"> Trả gốc đều hàng tháng, trả lãi theo dư nợ giảm dần </option>
                            <option value="3"> Trả theo phương thức niêm kim cố định </option>
                            <option value="2"> Trả gốc cuối kỳ, trả lãi hàng tháng theo dư nợ thực tế. </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="label"> Lãi suất </td>
                    <td>
                        <input type="text" id="laiSuat" name="laiSuat" value="">
                        <select id="theoThoiGian" name="theoThoiGian">
                            <option value="thang"> Theo tháng </option>
                            <option value="nam"> Theo năm </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="label"> Thời gian </td>
                    <td> <input type="text" name="thoiGianVay" id="thoiGianVay" value=""> Tháng </td>
                </tr>
                <tr>
                    <td class="label"> </td>
                    <td> <a href="#" onclick="Tools.loan_calculate();" class="btn-form btn-common"> Tính nhanh <span class="arrow-icon"> </span> </a> </td>
                </tr>
                </tbody>
            </table> 
            <div class="tool-hdr black-hdr"> Kết quả </div>
            <article>
                <table class="funding-tbl">
                    <tbody>
                    <tr>
                        <td class="label"> Ngày bắt đầu vay vốn </td>
                        <td id="inNgayBatDauVay"> </td>
                    </tr>
                    <tr>
                        <td class="label"> Số tiền vay: </td>
                        <td id="inSoTienVay"> </td>
                    </tr>
                    <tr>
                        <td class="label"> Hình thức trả nợ: </td>
                        <td id="inHinhThucTraNo"> </td>
                    </tr>
                    <tr>
                        <td class="label"> Lãi suất: </td>
                        <td id="inLaiXuatThucPhaiTra"> </td>
                    </tr>
                    <tr>
                        <td class="label"> Thời gian: </td>
                        <td id="inThoiGianVay"> </td>
                    </tr>
                    <tr>
                        <td class="label"> Tổng số tiền phải trả: </td>
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