<?php
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

?>
<div>
    <a class="tab active" data-step="step1" data-step-current="thanhpho">Tab1</a> |
    <a class="tab" data-step="step2" data-step-current="thanhpho">Tab2</a> |
    <a class="tab" data-step="step3" data-step-current="dstin">Tab3</a> |
</div>


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


<script>
    var steps = <?=json_encode($arr);?>;
	
	console.log(steps);

    $(document).on('click', '.tab', function () {
        var _this = $(this);
        _this.trigger('real-estate/actionDisplayList', [{'this': _this}, '']);

    });


    $(document).on('click', '.lstSuggest a', function () {
        var forceStep = $(this).attr('data-force-step');

        var _this = $('.tab.active');
        var stepCurrent = _this.attr('data-step-current');
        var step = _this.attr('data-step');
        var stepDisplay = null;

        if(steps[step][stepCurrent] && steps[step][stepCurrent][forceStep]){
            stepDisplay = steps[step][stepCurrent][forceStep];
        }else{
            stepDisplay = steps[step][stepCurrent]['next'];
        }

        _this.trigger('real-estate/actionDisplayList', [{'this': _this, 'stepDisplay': stepDisplay}, '']);
    });

    $(document).bind('real-estate/actionDisplayList', function (event, json, string) {
        var _this = json.this;
        var stepCurrent = _this.attr('data-step-current');
        var step = _this.attr('data-step');
        var stepDisplay = stepCurrent;
        if(json.stepDisplay){
            stepDisplay = json.stepDisplay;
        }

        $('.tab').removeClass('active');
        _this.addClass('active');

        _this.trigger('real-estate/displayList', [{step: stepDisplay, status: 1}, '']);
        if(steps[step][stepDisplay] && steps[step][stepDisplay]['prev']){
            _this.trigger('real-estate/displayList', [{step: steps[step][stepDisplay]['prev'], status: 0}, '']);
        }
        _this.attr('data-step-current', stepDisplay);
    });

    $(document).bind('real-estate/displayList', function (event, json, string) {
        if(json.status == 1){
//            $('.' + list).toggle();
            $('.' + json.step).show();
        }else{
            $('.' + json.step).hide();
        }

    });

    $(document).bind('real-estate/xx', function (event, json, string) {

    });

</script>

<style>
    .lstSuggest {
        display: none;
    }
</style>