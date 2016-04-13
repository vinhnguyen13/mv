<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 4/13/2016
 * Time: 9:52 AM
 */

//Yii::$app->getView()->registerJsFile('http://ancu.com/public/client/js/jquery.autoNumeric-1.7.5.js', ['position' => \yii\web\View::POS_BEGIN]);
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
                        <span style="position: relative;"><input type="text" name="ngayBatDau" id="ngayBatDau" globalnumber="823"><div class="JsDatePickBox" style="z-index: 3; position: absolute; top: 18px; left: 0px; display: none;"><div class="boxLeftWall"><div class="leftTopCorner"></div><div class="leftWall"></div><div class="leftBottomCorner"></div></div><div class="boxMain"><div class="boxMainInner"><div class="controlsBar" globalnumber="823"><div class="monthForwardButton" globalnumber="823"></div><div class="monthBackwardButton" globalnumber="823"></div><div class="yearForwardButton" globalnumber="823"></div><div class="yearBackwardButton" globalnumber="823"></div><div class="controlsBarText">April, 2016</div></div><div class="clearfix"></div><div class="tooltip"></div><div class="weekDaysRow"><div class="weekDay">Mon</div><div class="weekDay">Tue</div><div class="weekDay">Wed</div><div class="weekDay">Thu</div><div class="weekDay">Fri</div><div class="weekDay">Sat</div><div class="weekDay" style="margin-right: 0px;">Sun</div></div><div class="boxMainCellsContainer"><div class="skipDay"></div><div class="skipDay"></div><div class="skipDay"></div><div class="skipDay"></div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">1</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">2</div><div globalnumber="823" class="dayNormal" style="margin-right: 0px; background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">3</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">4</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">5</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">6</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">7</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">8</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">9</div><div globalnumber="823" class="dayNormal" style="margin-right: 0px; background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">10</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">11</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">12</div><div globalnumber="823" istoday="1" class="dayNormalToday" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">13</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">14</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">15</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">16</div><div globalnumber="823" class="dayNormal" style="margin-right: 0px; background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">17</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">18</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">19</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">20</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">21</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">22</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">23</div><div globalnumber="823" class="dayNormal" style="margin-right: 0px; background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">24</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">25</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">26</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">27</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">28</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">29</div><div globalnumber="823" class="dayNormal" style="background: url(&quot;../images/jsDatePicker/ocean_blue_dayNormal.gif&quot;) 0% 0% no-repeat;">30</div></div><div class="clearfix"></div></div></div><div class="boxRightWall"><div class="rightTopCorner"></div><div class="rightWall"></div><div class="rightBottomCorner"></div></div><div class="clearfix"></div><div class="jsDatePickCloseButton" globalnumber="823"></div><div class="topWall"></div><div class="bottomWall"></div></div></span>
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