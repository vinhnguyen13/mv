<?php
use yii\web\View;
Yii::$app->getView()->registerJsFile('http://code.highcharts.com/highcharts.js', ['position' => View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile('http://code.highcharts.com/modules/exporting.js', ['position' => View::POS_BEGIN]);
?>
<div class="col-xs-9 right-profile quanlytinraoban">
    <div class="wrap-quanly-profile">
        <div class="title-frm">Quản lý tin rao bán/cho thuê</div>
        <form class="frm-filter row" action="">
            <div class="form-group col-xs-3">
                <label for="exampleInputEmail1">Từ ngày</label>
                <div class="input-group">
                    <input id="date-picker-1" type="text" class="date-picker form-control" />
                    <label for="date-picker-1" class="input-group-addon btn">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </label>
                </div>
            </div>
            <div class="form-group col-xs-3">
                <label for="exampleInputEmail1">Đến ngày</label>
                <div class="input-group">
                    <input id="date-picker-2" type="text" class="date-picker form-control" />
                    <label for="date-picker-3" class="input-group-addon btn">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </label>
                </div>
            </div>
            <div class="form-group col-xs-3">
                <label for="exampleInputEmail1">Trạng thái</label>
                <select class="form-control">
                    <option>Trang thái 1</option>
                    <option>Trang thái 2</option>
                    <option>Trang thái 3</option>
                </select>
            </div>
            <div class="form-group col-xs-3">
                <label for="exampleInputEmail1">Loại giao dịch</label>
                <select class="form-control">
                    <option>Giao dịch 1</option>
                    <option>Giao dịch 2</option>
                    <option>Giao dịch 3</option>
                </select>
            </div>
        </form>

        <div class="tbl-list">
            <table class="table">
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Tiêu đề</th>
                    <th>Lượt xem</th>
                    <th>Bắt đầu</th>
                    <th>Kết thúc</th>
                    <th>Trạng thái</th>
                    <th>Biểu đồ</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td><a href="#">Nhà bán gấp 6 tỷ hẻm 8m Lạc Long Quân, Q11. 4mx16,5m</a></td>
                    <td>1.230</td>
                    <td>11/12/2015</td>
                    <td>01/01/2015</td>
                    <td>Còn hạn</td>
                    <td class="text-center"><a href="#"><em class="fa fa-area-chart"></em></a></td>
                </tr>
                <tr>
                    <th scope="row">1</th>
                    <td><a href="#">Nhà bán gấp 6 tỷ hẻm 8m Lạc Long Quân, Q11. 4mx16,5m</a></td>
                    <td>1.230</td>
                    <td>11/12/2015</td>
                    <td>01/01/2015</td>
                    <td>Còn hạn</td>
                    <td class="text-center"><a href="#"><em class="fa fa-area-chart"></em></a></td>
                </tr>
                <tr>
                    <th scope="row">1</th>
                    <td><a href="#">Nhà bán gấp 6 tỷ hẻm 8m Lạc Long Quân, Q11. 4mx16,5m</a></td>
                    <td>1.230</td>
                    <td>11/12/2015</td>
                    <td>01/01/2015</td>
                    <td class="row-hethan">Hết hạn <a class="push-post" href="#" title="push"><em class="fa fa-arrow-circle-up"></em></a></td>
                    <td class="text-center"><a href="#"><em class="fa fa-area-chart"></em></a></td>
                </tr>
                <tr>
                    <th scope="row">1</th>
                    <td><a href="#">Nhà bán gấp 6 tỷ hẻm 8m Lạc Long Quân, Q11. 4mx16,5m</a></td>
                    <td>1.230</td>
                    <td>11/12/2015</td>
                    <td>01/01/2015</td>
                    <td>Còn hạn</td>
                    <td class="text-center"><a href="#"><em class="fa fa-area-chart"></em></a></td>
                </tr>
                <tr>
                    <th scope="row">1</th>
                    <td><a href="#">Nhà bán gấp 6 tỷ hẻm 8m Lạc Long Quân, Q11. 4mx16,5m</a></td>
                    <td>1.230</td>
                    <td>11/12/2015</td>
                    <td>01/01/2015</td>
                    <td>Còn hạn</td>
                    <td class="text-center"><a href="#"><em class="fa fa-area-chart"></em></a></td>
                </tr>
                <tr>
                    <th scope="row">1</th>
                    <td><a href="#">Nhà bán gấp 6 tỷ hẻm 8m Lạc Long Quân, Q11. 4mx16,5m</a></td>
                    <td>1.230</td>
                    <td>11/12/2015</td>
                    <td>01/01/2015</td>
                    <td>Còn hạn</td>
                    <td class="text-center"><a href="#"><em class="fa fa-area-chart"></em></a></td>
                </tr>
                </tbody>
            </table>
            <ul class="pagination text-right">
                <li>
                    <a href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li>
                    <a href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>