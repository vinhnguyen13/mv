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
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/search-vsoft.js', ['position'=>View::POS_END]);
?>
<?php $this->beginContent('@app/views/layouts/_partials/js/jsContainer.php', ['options'=>[]]); ?><?php $this->endContent();?>
<div class="">
    <ul class="nav nav-tabs">
        <li class="active" data-step-config="step1" data-step-current="thanhpho" data-step-default="thanhpho"><a class="tab">Muốn Mua/Thuê</a></li>
        <li data-step-config="step2" data-step-current="thanhpho" data-step-default="thanhpho"><a class="tab">Đăng ký Bán/Thuê</a></li>
        <li data-step-config="step3" data-step-current="dstin" data-step-default="dstin"><a class="tab">Tin Tức</a></li>
    </ul>
    <div class="wrapClickSearch">
        <div class="textSelected">
<!--             <span class="tag label label-info" data-value="1" data-step-selected="thanhpho">Ho Chi Minh <span class="remove">REMOVE</span></span>
            <span class="tag label label-info" data-value="1" data-step-selected="tinhthanh">Quan 1 <span class="remove">REMOVE</span></span>
            <span class="tag label label-info" data-value="1" data-step-selected="loaibds">Chung Cu <span class="remove">REMOVE</span></span>
            <span class="tag label label-info" data-value="1" data-step-selected="lstduan">Lancaster <span class="remove">REMOVE</span></span> -->
        </div>
        <input class="searchInput">
    </div>
    <div class="wrapSuggestList">
        <a class="close">Close</a>
        <div class="suggestListData">

        </div>
    </div>
</div>




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