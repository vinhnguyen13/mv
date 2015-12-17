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
$stt = '{"step1":["thanhpho","tinhthanh",["loaibds","giaca"]],"step2":["thanhpho","tinhthanh","loaibds"],"step3":{"tintuc":["loaitintuc"],"duan":["thanhpho","tinhthanh","duans"]}}';
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/search-vsoft.js', ['position'=>View::POS_END]);
?>
<?php $this->beginContent('@app/views/layouts/_partials/js/jsContainer.php', ['options'=>[]]); ?><?php $this->endContent();?>

<div class="">
    <ul class="nav nav-tabs">
        <li class="active" data-step="step1" data-step-current="thanhpho"><a class="tab">Muốn Mua/Thuê</a></li>
        <li data-step="step2" data-step-current="thanhpho"><a class="tab">Đăng ký Bán/Thuê</a></li>
        <li data-step="step3" data-step-current="dstin"><a class="tab">Tin Tức</a></li>
    </ul>
    <div class="wrapClickSearch">
        <div class="textSelected">
            <span class="text">ABC</span>
            <a class="remove">x</a>
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
    <ul class="loaitintuc lstSuggest">
        <li>
            <a href="#">Tin BDS</a>
        </li>
        <li>
            <a href="#">Tin XXX</a>
        </li>
    </ul>

    <ul class="thanhpho lstSuggest">
        <li>
            <a href="#">Ho Chi Minh</a>
        </li>
        <li>
            <a href="#">Ha Noi</a>
        </li>
    </ul>

    <ul class="tinhthanh lstSuggest">
        <li>
            <a href="#">Quan 1</a>
        </li>
        <li>
            <a href="#">Quan 2</a>
        </li>
    </ul>

    <ul class="loaibds lstSuggest">
        <li>
            <a href="#" data-force-step="chung-cu">Chung Cu</a>
        </li>
        <li>
            <a href="#">Nha Rieng</a>
        </li>
    </ul>

    <ul class="lstduan lstSuggest">
        <li>
            <a href="#">Du An 1</a>
        </li>
        <li>
            <a href="#">Du An 2</a>
        </li>
    </ul>

    <ul class="giaca lstSuggest">
        <li>
            <a href="#">1000</a>
        </li>
        <li>
            <a href="#">99999</a>
        </li>
    </ul>

    <ul class="dstin lstSuggest">
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