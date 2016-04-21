<?php
use yii\web\View;
$arr = [
    "step1"=>[
        "thanhpho"=>["id"=>"thanhpho", "next"=>"tinhthanh"],
        "tinhthanh"=>["id"=>"tinhthanh", "next"=>"loaibds", "prev"=>"thanhpho"],
        "loaibds"=>["id"=>"loaibds", "next"=>"giaca", "prev"=>"tinhthanh", "chung-cu"=>"lstduan"],
        "lstduan"=>["id"=>"lstduan", "prev"=>"loaibds"],
        "giaca"=>["id"=>"giaca", "prev"=>"loaibds"],
    ],
    "step2"=>[
        "thanhpho"=>["id"=>"thanhpho", "next"=>"tinhthanh"],
        "tinhthanh"=>["id"=>"tinhthanh", "next"=>"loaibds", "prev"=>"thanhpho"],
        "loaibds"=>["id"=>"loaibds", "prev"=>"tinhthanh"],
    ],
    "step3"=>[
        "dstin"=>["id"=>"dstin", "tin"=>"loaitintuc", 'duan'=>'thanhpho'],
        "loaitintuc"=>["id"=>"loaitintuc", "prev"=>"dstin"],
        "thanhpho"=>["id"=>"thanhpho", "next"=>"tinhthanh", "prev"=>"dstin"],
        "tinhthanh"=>["id"=>"tinhthanh", "next"=>"lstduan", "prev"=>"thanhpho"],
        "lstduan"=>["id"=>"lstduan", "prev"=>"tinhthanh"],

    ]
];
$this->beginContent('@app/views/layouts/_partials/head/container.php', ['options'=>[]]); ?><?php $this->endContent();
$this->registerCssFile('https://select2.github.io/dist/css/select2.min.css', ['depends' => ['yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile ( 'https://select2.github.io/dist/js/select2.full.js', ['position' => View::POS_BEGIN]);

Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/search-vsoft.js', ['position'=>View::POS_END]);
?>
<div class="">
    <ul class="nav nav-tabs">
        <li class="active" data-step-config="step1" data-step-current="thanhpho" data-step-default="thanhpho"><a class="tab">Muốn Mua/Thuê</a></li>
        <li data-step-config="step2" data-step-current="thanhpho" data-step-default="thanhpho"><a class="tab">Đăng ký Bán/Thuê</a></li>
        <li data-step-config="step3" data-step-current="dstin" data-step-default="dstin"><a class="tab">Tin Tức</a></li>
    </ul>
    <div class="wrapClickSearch">
        <div class="textSelected">
        </div>
        <input class="searchInput">
    </div>
    <div class="wrapSuggestList">
        <a class="close">Close</a>
        <div class="suggestListData">

        </div>
    </div>
</div>

<select id="example" class="thanhpho form-control" multiple="multiple" style="width: 200px;"></select>
<script>
$(document).ready(function() {
    var data = [{ id: 0, text: 'enhancement' }, 
    { id: 1, text: 'bug' }, 
    { id: 2, text: 'duplicate' }, 
    { id: 3, text: 'invalid' }, 
    { id: 4, text: 'wontfix' }];

var select = $("#example").select2({
        data: data,
    });
    select.data = data;
//     $("#example").select2({
//         data: data,
//     });

    $(document).on('click', '.searchInput', function () {
        var data2 = [{ id: 0, text: 'xxxx' }];
         $("#example").select2('val','');
        $("#example").data('select2_values', data2);

//         select.data = data2;
		alert(4);
	});

});
</script>




<div style="display: none">
    <ul class="loaitintuc">
        <li>
            <a href="#">Tin BDS</a>
        </li>
        <li>
            <a href="#">Tin XXX</a>
        </li>
    </ul>

    <ul class="thanhpho">
        <li>
            <a href="#" data-child="tinhthanh" data-value="1">Ho Chi Minh</a>
        </li>
        <li>
            <a href="#" data-child="tinhthanh" data-value="2">Ha Noi</a>
        </li>
    </ul>

    <ul data-thanhpho="1" class="tinhthanh">
        <li>
            <a href="#">Quan 1</a>
        </li>
        <li>
            <a href="#">Quan 2</a>
        </li>
    </ul>

    <ul data-thanhpho="2" class="tinhthanh">
        <li>
            <a href="#">Hoan Kiem</a>
        </li>
        <li>
            <a href="#">Ba Dinh</a>
        </li>
    </ul>

    <ul class="loaibds">
        <li>
            <a href="#" data-force-step="chung-cu">Chung Cu</a>
        </li>
        <li>
            <a href="#">Nha Rieng</a>
        </li>
    </ul>

    <ul class="lstduan">
        <li>
            <a href="#">Du An 1</a>
        </li>
        <li>
            <a href="#">Du An 2</a>
        </li>
    </ul>

    <ul class="giaca">
        <li>
            <a href="#">1000</a>
        </li>
        <li>
            <a href="#">99999</a>
        </li>
    </ul>

    <ul class="dstin">
        <li>
            <a href="#" data-force-step="tin">Tin tuc</a>
        </li>
        <li>
            <a href="#" data-force-step="duan">Du An</a>
        </li>
    </ul>
</div>


<script>
    var steps = <?=json_encode($arr);?>;


</script>

<style>
    /*.suggestList {*/
        /*width: 100%;*/
        /*background-color: #cccccc;*/
        /*position:absolute;*/
    /*}*/
</style>