<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 4/13/2016
 * Time: 9:52 AM
 */

?>
<div class="title-fixed-wrap container">
    <div class="tool-cacu">
        <div class="news_blocks_contain">
            <div class="title-top"> Cách tính điểm của MetVuong.com </div>
            <div class="sum"> Chúng tôi cung cấp cho bạn công cụ tính điểm của tin của bạn trên MetVuong, để các bạn tiện cho việc theo dõi hiệu suất xuất hiện tin đăng của mình trên MetVuong </div>
            <table class="funding-tbl">
                <tbody id="thongTinVayVon">
                <tr>
                    <td class="label"> Ngày đăng tin </td>
                    <td>
                        <?=\yii\jui\DatePicker::widget([
                            'language' => 'en',
                            'dateFormat' => 'yyyy-MM-dd',
                            'value' => date("Y-m-d H:i:s", strtotime('-7days')),
                            'options' => [
                                // you can hide the input by setting the following
                                'id' => 'ngayBatDau',
                            ]
                        ]);
                        ?>

                    </td>
                </tr>
                <tr>
                    <td class="label"> Điểm tổng </td>
                    <td>
                        <input type="text" id="tongDiem" name="tongDiem" value="100">
                    </td>
                </tr>
                <tr>
                    <td class="label"> Tỷ lệ </td>
                    <td>
                        <input type="text" id="tyle" name="tyle" value="2">
                        %
                    </td>
                </tr>
                <tr>
                    <td class="label"> </td>
                    <td> <a href="#" class="btn-form btn-common btn-tinhnhanh"> Tính nhanh <span class="arrow-icon"> </span> </a> </td>
                </tr>
                </tbody>
            </table>
            <div class="tool-hdr black-hdr"> Kết quả </div>
            <article id="inKetQua">
<!--                <table class="savings-tbl">-->
<!--                    <tbody>-->
<!--                        <tr class="savings-tlt">-->
<!--                            <td>Ngày</td>-->
<!--                            <td>Điểm ngày trước</td>-->
<!--                            <td>Điểm hiện tại</td>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <td>6/7/2016</td>-->
<!--                            <td class="saving_table saving_table_left">100</td>-->
<!--                            <td class="saving_table">98</td>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <td>6/7/2016</td>-->
<!--                            <td class="saving_table saving_table_left">98</td>-->
<!--                            <td class="saving_table">96.04</td>-->
<!--                        </tr>-->
<!--                    </tbody>-->
<!--                </table>-->

            </article>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var calculation = function (score, percent) {
            return score - (score*percent/100);
        };

        $(document).on('click', '.btn-tinhnhanh', function (e) {
            var score = $('#tongDiem').val();
            var percent = $('#tyle').val();

            var date1 = $('#ngayBatDau').datepicker('getDate');
            var date2 = new Date();



            if (date1 && date2) {
                diff = Math.floor((date2.getTime() - date1.getTime()) / 86400000); // ms per day
            }

            var d = new Date();

            console.log(date1, '______________________', $.datepicker.formatDate( "dd/mm/yy", date1 ) );

            var prevScore, curScore = score;

            var html = '<table class="savings-tbl"><tbody><tr class="savings-tlt"><td>Ngày</td><td>Điểm ngày trước</td><td>Điểm hiện tại</td></tr>';
            for(var i = 1; i <= diff; i++){
                if(prevScore != curScore){
                    prevScore = curScore;
                }
                curScore = calculation(prevScore, percent);
                date1.setTime(date1.getTime() + 86400000);
                var currentDate = $.datepicker.formatDate( "dd/mm/yy", date1 );
                html+='<tr><td>'+currentDate+'</td><td class="saving_table saving_table_left">'+prevScore+'</td><td class="saving_table">'+curScore+'</td></tr>';
            }
            html+='</tbody></table>';
            $('#inKetQua').html(html);
        });
    });
</script>