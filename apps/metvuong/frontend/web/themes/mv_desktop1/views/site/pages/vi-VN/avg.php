<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 4/13/2016
 * Time: 9:52 AM
 */
use yii\helpers\Html;
use vsoft\ad\models\AdCity;
use vsoft\ad\models\AdDistrict;
use vsoft\ad\models\AdCategory;
use vsoft\ad\models\AdProduct;
use yii\helpers\ArrayHelper;
use yii\web\View;

$this->registerCssFile(Yii::$app->view->theme->baseUrl . '/resources/css/select2.min.css');
$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/jquery-ui.min.js', ['position' => View::POS_END]);
$this->registerJsFile(Yii::$app->view->theme->baseUrl . '/resources/js/select2.full.min.js', ['position' => View::POS_END]);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/df-number-format/2.1.6/jquery.number.min.js', ['position' => View::POS_END]);


$citiesDropdown = ArrayHelper::map(AdCity::find()->all(), 'id', 'name');

$districtDropdown = ArrayHelper::map(AdDistrict::find()->all(), 'id', 'name');

$categories = AdCategory::find()->orderBy('order')->all();
foreach ($categories as $category) {
    $categoriesDropDown[$category->id] = ucfirst(Yii::t('ad', $category->name));
}
//$categoriesDropDown = ArrayHelper::map($categories, 'id', 'name');
?>
<div class="title-fixed-wrap container">
    <div class="tool-cacu">
        <div class="wrap-frm-listing">
            <div class="group-frm">
                <form id="frmAvg">
                    <div class="title-frm">Thành phố / Quận-Huyện / Phường-Xã</div>
                    <div class="row region">
                        <div class="form-group col-xs-12 col-sm-6 wrap_type">
                            <label>Hình thức</label>
                            <?=Html::dropDownList('type', null, [AdProduct::TYPE_FOR_SELL => Yii::t('ad', 'Sell'),AdProduct::TYPE_FOR_RENT => Yii::t('ad', 'Rent'),], ['class' => 'form-control search region_type', 'prompt' => "..."])?>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 wrap_category">
                            <label>Loại BDS</label>
                            <?=Html::dropDownList('category', null, $categoriesDropDown, ['class' => 'form-control search region_category', 'prompt' => "..."])?>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 wrap_project" style="display: none;">
                            <label>Dự Án</label>
                            <?=Html::dropDownList('project_building_id', null, [], ['class' => 'form-control search region_project', 'prompt' => "..."])?>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 wrap_city">
                            <label>Thành Phố </label>
                            <?=Html::dropDownList('city', null, $citiesDropdown, ['class' => 'form-control search region_city', 'prompt' => "..."])?>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 wrap_district" style="display: none;">
                            <label>Quận </label>
                            <?=Html::dropDownList('district', null, $districtDropdown, ['class' => 'form-control search region_district', 'prompt' => "..."])?>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 wrap_wards" style="display: none;">
                            <label>Phường </label>
                            <?=Html::dropDownList('wards', null, [], ['class' => 'form-control search region_wards', 'prompt' => "..."])?>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 wrap_streets" style="display: none;">
                            <label>Đường </label>
                            <?=Html::dropDownList('streets', null, [], ['class' => 'form-control search region_streets', 'prompt' => "..."])?>
                        </div>
                    </div>
                    <a href="javascript:;" class="btn-form btn-common btn-tinhnhanh"> Tính nhanh <span class="arrow-icon"> </span> </a>
                </form>
            </div>
            <div class="tool-hdr black-hdr"> Kết quả</div>
            <article id="inKetQua">

            </article>
            <div id="chartAds" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        </div>
    </div>
</div>
<script src="//code.highcharts.com/highcharts.js"></script>
<script src="//code.highcharts.com/highcharts-more.js"></script>
<script>
    $(document).ready(function () {
        var func = {
            appendDropdown: function(el, items) {
                el.find("option:not(:first-child)").remove();
                for(var i in items) {
                    if(items[i]['pre']) {
                        el.append('<option data-pre="' + items[i]['pre'] + '" value="' + items[i]['id'] + '">' + items[i]['name'] + '</option>');
                    } else {
                        el.append('<option value="' + items[i]['id'] + '">' + items[i]['name'] + '</option>');
                    }
                }
                el.parent().show();
                el.select2();
            },
            pushOptionTextToArray: function(elClass, text) {
                if($('.'+elClass).val()){
                    text.push($('.'+elClass+' option:selected').text());
                }
                return text;
            }
        };
        $('.region_city').select2();
        $('.region_project').select2({
            width: '100%',
            ajax: {
                url: '/map/search-project',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        v: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.full_name,
                                slug: item.full_name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            },
        });
        $(document).on('change', '.region_category', function (e) {
            if($('.region_category').val() == 6){
                $('.wrap_project').show();
                /*$('.wrap_city').hide();
                $('.wrap_district').hide();
                $('.wrap_wards').hide();
                $('.wrap_streets').hide();*/
            }else{
                $('.wrap_project').hide();
                $('.wrap_city').show();
            }
            $('.region_city').select2("val", "");
            $('.region_project').select2("val", "");
        });

        $(document).on('change', '.region_city', function (e) {
            var form = $('.region');
            if($(this).val()){
                $.get('/listing/list-district', {cityId: $(this).val()}, function(districts){
                    func.appendDropdown($('.region_district'), districts);
                });
            }
        });
        $(document).on('change', '.region_district', function (e) {
            var form = $('.region');
            if($(this).val()){
                $.get('/listing/list-sw', {districtId: $(this).val()}, function(districts){
                    func.appendDropdown($('.region_wards'), districts.wards);
                    func.appendDropdown($('.region_streets'), districts.streets);
                });
            }
        });

        $(document).on('click', '.btn-tinhnhanh', function (e) {
            if($('.region_city').val() || $('.region_project').val()) {
                $.post('/site/avg', $('#frmAvg').serialize(), function (response) {
                    var html = '<table class="savings-tbl"><tbody><tr class="savings-tlt"><td>Điều kiện</td><td>Giá Trung Bình</td><td>Giá Trung Bình/m2</td><td>Listing</td></tr>';
                    var text = [];
                    func.pushOptionTextToArray('region_category', text);
                    func.pushOptionTextToArray('region_city', text);
                    func.pushOptionTextToArray('region_district', text);
                    var ArrayData = $.map(response.list_price.split(','), function(value){
                        return parseInt(value, 10);
                        // or return +value; which handles float values as well
                    });
                    loadChart([ArrayData]);
                    console.log(ArrayData);
//                    loadChart([
//                        [5700, 2200, 8400, 8000, 3300, 1950, 8320, 8000, 9, 8320, 9000, 8300],
//                    ]);
                    var avg = response.sum / response.total;
                    var avg_m2 = response.sum / response.sum_area;
                    html += '<tr><td class="saving_table saving_table_left">' + text.join(', ') + '</td><td class="saving_table">' + $.number(avg) + ' VND</td><td class="saving_table">' + $.number(avg_m2) + ' VND</td><td class="saving_table">' + $.number(response.totalListing) + '</td></tr>';
                    html += '</tbody></table>';
                    $('#inKetQua').html(html);
                });
            }else{
                alert('Vui lòng chọn Thành Phố');
            }

        });

        function loadChart(data){
            $('#chartAds').highcharts({
                chart: {
                    type: 'boxplot'
                },
                title: {
                    text: 'MetVuong Box Plot Chart'
                },
                legend: {
                    enabled: false
                },
                xAxis: {
                    categories: ['1'],
                    title: {
                        text: 'Experiment No.'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Observations'
                    }
                },
                plotOptions: {
                    boxplot: {
                        fillColor: '#F0F0E0',
                        lineWidth: 2,
                        medianColor: '#0C5DA5',
                        medianWidth: 3,
                        stemColor: '#A63400',
                        stemDashStyle: 'dot',
                        stemWidth: 1,
                        whiskerColor: '#3D9200',
                        whiskerLength: '20%',
                        whiskerWidth: 3
                    }
                },
                series: [{
                    name: 'Observations',
                    data: data
                }]

            });
        }
    });
</script>