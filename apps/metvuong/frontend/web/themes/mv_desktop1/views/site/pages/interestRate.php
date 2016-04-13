<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 4/13/2016
 * Time: 9:52 AM
 */
Yii::$app->getView()->registerJsFile('http://ancu.com/public/client/js/utilities.js', ['position' => View::POS_BEGIN]);
?>

<form id="frmSubmit">
    <ul class="formtool">
        <li>
            <label>Số tiền vay:</label>
            <input type="text" name="money" value="" style="width:58%">                        </li>
        <li>
            <label>Thời gian vay:</label>
            <input type="text" name="time" value="" style="width:24%">						    <select name="time_type">
                <option value="0">Tháng</option>
                <option value="1">Năm</option>
            </select>                            <!--<select name="time_type">
                            	<option value="0">Tháng</option>
                            	<option value="1">Năm</option>
                            </select>-->
        </li>
        <li>
            <label>Lãi xuất (%)</label>
            <input type="text" name="interest_rate" value="" style="width:24%">                            <select name="interest_type">
                <option value="0">Tháng</option>
                <option value="1">Năm</option>
            </select>                        </li>
        <li>
            <label>Loại hình</label>
            <select name="type" style="width: 62%;">
                <option value="2">Trả góp đều hàng tháng theo lãi suất kép</option>
                <option value="3">Trả góp đều hàng tháng theo lãi suất đơn</option>
                <option value="4">Trả góp đều, lãi tính trên dư nợ giảm dần hàng tháng</option>
                <option value="5">Trả góp theo dư nợ giảm dần</option>
            </select>                        </li>
        <li><a href="javascript:" class="btnview marginr" id="btnSubmit">Tính lãi</a></li>
    </ul>
</form>