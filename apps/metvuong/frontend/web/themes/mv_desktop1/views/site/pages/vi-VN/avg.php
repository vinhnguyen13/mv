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

$groupCategories = Yii::$app->db->cache(function(){
    return \vsoft\ad\models\AdCategoryGroup::find()->all();
});
foreach ($groupCategories as $category) {
    $categoriesDropDown[$category->id] = ucfirst(Yii::t('ad', $category->name));
}
$groupCategories = Yii::$app->db->cache(function(){
    return \vsoft\ad\models\AdCategoryGroup::find()->all();
});
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
                        <div class="form-group col-xs-12 col-sm-6 num-phongngu">
                            <label>Phòng ngủ </label>
                            <div class="box-dropdown dropdown-common">
                                <div class="val-selected style-click" data-text-add="<?= Yii::t('ad', 'Beds') ?>"><span class="selected">0+ <?= Yii::t('ad', 'Beds') ?></span><span class="arrowDownFillFull"></span></div>
                                <div class="item-dropdown item-bed-bath hide-dropdown">
                                    <ul class="clearfix">
                                        <li><a href="#" data-value="">0+</a></li>
                                        <li><a href="#" data-value="1">1+</a></li>
                                        <li><a href="#" data-value="2">2+</a></li>
                                        <li><a href="#" data-value="3">3+</a></li>
                                        <li><a href="#" data-value="4">4+</a></li>
                                        <li><a href="#" data-value="5">5+</a></li>
                                    </ul>
                                    <input type="hidden" value="" name="" id="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 num-phongtam">
                            <label>Phòng tắm </label>
                            <div class="box-dropdown dropdown-common">
                                <div class="val-selected style-click" data-text-add="<?= Yii::t('ad', 'Bath') ?>"><span class="selected">0+ <?= Yii::t('ad', 'Bath') ?></span><span class="arrowDownFillFull"></span></div>
                                <div class="item-dropdown item-bed-bath hide-dropdown">
                                    <ul class="clearfix">
                                        <li><a href="#" data-value="">0+</a></li>
                                        <li><a href="#" data-value="1">1+</a></li>
                                        <li><a href="#" data-value="2">2+</a></li>
                                        <li><a href="#" data-value="3">3+</a></li>
                                        <li><a href="#" data-value="4">4+</a></li>
                                        <li><a href="#" data-value="5">5+</a></li>
                                    </ul>
                                    <input type="hidden" value="" name="" id="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 choice_price_dt select-dt">
                            <label>Diện tích </label>
                            <div class="box-dropdown" data-item-minmax="area">
                                <div class="val-selected style-click dt-search">
                                    <span class="txt-holder-minmax"><?= Yii::t('ad', 'Size') ?></span>
                                    <div>
                                        <span class="wrap-min"></span>
                                        <span class="trolen">+</span>
                                        <span class="den">-</span>
                                        <span class="wrap-max"></span>
                                        <span class="troxuong"><?= Yii::t('ad', 'below') ?></span>
                                    </div>
                                    <span class="arrowDownFillFull"></span>
                                </div>
                                <div class="item-dropdown hide-dropdown wrap-min-max">
                                    <div class="box-input clearfix">
                                        <span class="txt-min min-max active min-val" data-value="" data-text="<?= Yii::t('ad', 'Min') ?>"><?= Yii::t('ad', 'Min') ?></span>
                                        <span class="text-center"><span></span></span>
                                        <span class="txt-max min-max max-val" data-value="" data-text="<?= Yii::t('ad', 'Max') ?>"><?= Yii::t('ad', 'Max') ?></span>
                                        <input type="hidden" id="dtMin" name="size_min">
                                        <input type="hidden" id="dtMax" name="size_max">
                                    </div>
                                    <div class="filter-minmax clearfix">
                                        <ul data-wrap-minmax="min-val" class="wrap-minmax"></ul>
                                        <ul data-wrap-minmax="max-val" class="wrap-minmax"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="javascript:;" class="btn-form btn-common btn-tinhnhanh"> Tính nhanh <span class="arrow-icon"> </span> </a>
                </form>
            </div>
            <div class="tool-hdr black-hdr"> Kết quả</div>
            <article id="inKetQua">

            </article>
        </div>
    </div>
</div>
<script src="//code.highcharts.com/highcharts.js"></script>
<script src="//code.highcharts.com/highcharts-more.js"></script>
<script>
    $(document).ready(function () {
        $('#frmAvg .dropdown-common').dropdown({
            txtAdd: true,
            styleShow: 0
        });

        $('#frmAvg .select-dt .box-dropdown').price_dt();

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
            $('.group-frm').loading({full: false});
            if($('.region_city').val() || $('.region_project').val()) {
                $.post('/site/avg', $('#frmAvg').serialize(), function (response) {
                    $('#inKetQua').html(response);
                    var text = [];
                    func.pushOptionTextToArray('region_category', text);
                    func.pushOptionTextToArray('region_city', text);
                    func.pushOptionTextToArray('region_district', text);
                    $('#inKetQua').find('.saving_table_left').html(text.join(', '));
                    $('body').loading({done:true});
                    return false;
                });
            }else{
                alert('Vui lòng chọn Thành Phố');
                $('body').loading({done:true});
            }

        });


    });
</script>