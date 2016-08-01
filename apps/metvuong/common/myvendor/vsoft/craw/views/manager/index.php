<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use funson86\cms\Module;
use vsoft\craw\models\AdCategory;
use vsoft\craw\models\AdProduct;
use yii\helpers\Url;
use vsoft\express\components\StringHelper;
use yii\web\View;
use vsoft\craw\models\AdProductAdditionInfo;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CmsShowSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->registerCss('.filter-col {margin-right: 12px;} .container {max-width: none; width: auto;} .summary {float: right;font-size: 20px;margin-top: 28px;} .title {float: left;} .min {width: 100px; display: inline-block;} table {white-space: nowrap;}');


$script = <<<EOD
	if(!getCookie('cols')) {
		setCookie('cols', '2,10,12,13,14,15,16,17,18,19,21,22');
	}

	$('.price').keyup(function(){
		formatCurrency($(this));
	}).each(function(){
		formatCurrency($(this));
	});
	
	$('input[type=text]').change(function(e){
		e.stopPropagation();
	});
	
	function formatCurrency(el) {
		var val = el.val();
		var next = el.next();
		var text = '';
		
		if(/^0*$/.test(val)) {
			next.text('');
		} else {
			if(val) {
				text = val.split( /(?=(?:\d{3})+(?:\.|$))/g ).join(".");
				
				if(val.length > 9) {
					text = (val / 1000000000) + '';
					text = formatNumber(text.replace('.', ',')) + ' tỷ';
				} else if(val.length > 6) {
					text = (val / 1000000) + '';
					text = text.replace('.', ',') + ' triệu';
				}
				
				if($('#adproduct-type').val() == 2) {
					text += '/tháng';
				}
			}
			
			next.text(text);
		}
	}
	
	function formatNumber(val) {
		if(/^0*$/.test(val)) {
			return '';
		}
		
		val = val.split(',');
		var text = val[0];
		text = text.split( /(?=(?:\d{3})+(?:\.|$))/g ).join(".");
		
		if(val.length > 1) {
			text = text + ',' + val[1];
		}
		
		return text;
	}
		
	function setCookie(cname, cvalue, exdays) {
	    var d = new Date();
	    d.setTime(d.getTime() + (exdays*24*60*60*1000));
	    var expires = "expires="+d.toUTCString();
	    document.cookie = cname + "=" + cvalue + "; " + expires;
	}
		
	function getCookie(cname) {
	    var name = cname + "=";
	    var ca = document.cookie.split(';');
	    for(var i=0; i<ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0)==' ') c = c.substring(1);
	        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
	    }
	    return "";
	}
		
	function setCol() {
		var p = [];
		$('#filter-column').find('input').each(function(){
			var index = $(this).attr('id').replace('col-', '');
			if(!$(this).prop('checked')) {
				p.push(index);
			}
		});
		setCookie('cols', p.join(','));
	}
	function getCol() {
		return getCookie('cols').split(',');
	}

	$('th').each(function(index, val){
		var cols = getCol();
		if(cols.indexOf(index + '') == -1) {
			var input = $('<label class="filter-col"><input checked="checked" type="checkbox" id="col-' + index + '" /> ' + $(this).text() + '</label>');
		} else {
			var input = $('<label class="filter-col"><input type="checkbox" id="col-' + index + '" /> ' + $(this).text() + '</label>');
			$('tr').each(function(){
				$(this).children().eq(index).hide();
			});
		}
		
		$('#filter-column').append(input);
		input.find('input').change(function(){
			var index = $(this).attr('id').replace('col-', '');
			if($(this).prop('checked')) {
				$('tr').each(function(){
					$(this).children().eq(index).show();
				});
			} else {
				$('tr').each(function(){
					$(this).children().eq(index).hide();
				});
			}
			setCol();
		});
	});

    $('.modal').each(function () {
        var t = $(this),
            d = t.find('.modal-dialog'),
            fadeClass = (t.is('.fade') ? 'fade' : '');
        // render dialog
        t.removeClass('fade')
            .addClass('invisible')
            .css('display', 'block');
        // read and store dialog height
        d.data('height', d.height());
        // hide dialog again
        t.css('display', '')
            .removeClass('invisible')
            .addClass(fadeClass);
    });

	$('#file-info').on('show.bs.modal', function (event) {
	    var t = $(this),
            d = t.find('.modal-dialog'),
            dh = d.data('height'),
            w = $(window).width(),
            h = $(window).height();
        // if it is desktop & dialog is lower than viewport
        // (set your own values)
        if (w > 380 && (dh + 60) < h) {
            d.css('margin-top', Math.round(0.96 * (h - dh) / 2));
        } else {
            d.css('margin-top', '');
        }

        var a = $(event.relatedTarget);
        var filename = a.data('filename');
        var filepath = a.data('filepath');
        var imported = a.data('imported');
        var toolid = a.data('toolid');
        var copied = a.data('copied');
        var mainid = a.data('mainid');

        var modal = $(this);
        modal.find('.modal-body .filename').html(filename);
        modal.find('.modal-body .filepath').html(filepath);
        modal.find('.modal-body .toolid').html(toolid);
        modal.find('.modal-body .imported').html(imported);
        modal.find('.modal-body .mainid').html(mainid);
        modal.find('.modal-body .copied').html(copied);
    });

EOD;
$this->registerJs($script, View::POS_READY);

$this->title = Yii::t('cms', 'Listing Management');

$type = [
	AdProduct::TYPE_FOR_SELL => Yii::t ( 'ad', 'Nhà đất bán' ),
	AdProduct::TYPE_FOR_RENT => Yii::t ( 'ad', 'Nhà đất cho thuê' )
];

$directionList = AdProductAdditionInfo::directionList();
$projectList = ArrayHelper::map(\vsoft\craw\models\AdBuildingProject::find()->where(['city_id' =>  1])->orderBy(['city_id' => SORT_ASC, 'name' => SORT_ASC])->all(), 'name', 'name');
?>
<div class="cms-show-index" style="position: relative;">
	<div style="text-align: center; position: absolute; width: 100%;"><a style="font-size: 20px; display: inline-block; margin-top: 22px;" href="<?= Url::to(['/craw/manager']) ?>">Reset Filter</a></div>
	<h2 class="title">Danh sách tin craw</h2>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $filterModel,
    	'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => '<span style="color: red; font-style: italic;">[null]</span>'],
    	'summary' => '<div class="summary">Hiển thị <b>{begin}-{end}</b> của <b>{totalCount}</b> tin.</div><div id="filter-column" style="clear: both;"></div>',
        'columns' => [
            'id',
			['attribute' => 'category_id', 'value' => 'category.name', 'filter' => Html::activeDropDownList($filterModel, 'category_id', ArrayHelper::map(AdCategory::find()->all(), 'id', 'name'), ['class' => 'form-control', 'prompt' => 'Chọn loại tin'])],
			['format' => 'raw', 'attribute' => 'content', 'value' => function($model) { return $model->content ? mb_substr($model->content, 0, 20, 'UTF-8') . '...' : '<span style="color: red; font-style: italic;">[null]</span>'; },
				'filter' => Html::activeDropDownList($filterModel, 'content', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
			],
			['attribute' => 'project', 'value' => 'project.name',
                'filter' => \kartik\select2\Select2::widget([
                    'model' => $filterModel,
                    'attribute' => 'project',
                    'data' => $projectList,
                    'options' => ['placeholder' => 'Select a project ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])
            ],
    		['attribute' => 'home_no', 'value' => 'home_no', 'filter' => Html::activeDropDownList($filterModel, 'homeNoFilter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])],
    		['attribute' => 'ward', 'value' => 'ward.fullName', 'filter' =>
    			Html::activeTextInput($filterModel, 'ward', ['class' => 'form-control']) .
				Html::activeDropDownList($filterModel, 'wardFilter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
			],
    		['attribute' => 'district', 'value' => 'district.fullName'],
    		['attribute' => 'street', 'value' => 'street.fullName', 'filter' =>
    			Html::activeTextInput($filterModel, 'street', ['class' => 'form-control']) .
				Html::activeDropDownList($filterModel, 'streetFilter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
			],
    		['attribute' => 'city', 'value' => 'city.name'],
    		['attribute' => 'type', 'value' => function($m) { return $m->getTypeText(); }, 'filter' => Html::activeDropDownList($filterModel, 'type', $type, ['class' => 'form-control', 'prompt' => 'Chọn hình thức'])],
            [
            	'format' => 'raw',
            	'attribute' => 'area',
            	'value' => function($model) {return $model->area ? $model->area : '<span style="color: red; font-style: italic;">[null]</span>';},
            	'filter' =>
	            	Html::activeTextInput($filterModel, 'areaMin', ['class' => 'form-control', 'placeholder' => 'min']) .
	            	Html::activeTextInput($filterModel, 'areaMax', ['class' => 'form-control', 'placeholder' => 'max']) .
	            	Html::activeDropDownList($filterModel, 'areaFilter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
			],
			[
				'format' => 'raw',
				'attribute' => 'price',
				'value' => function($model) {return $model->price_type ? StringHelper::formatCurrency($model->price) : '<span style="color: red; font-style: italic;">[thỏa thuận]</span>';},
				'filter' =>
					'<div style="white-space: nowrap">' . Html::activeTextInput($filterModel, 'priceMin', ['class' => 'form-control min price', 'placeholder' => 'min']) . ' <span class="price-format"></span>' . '</div>' .
					'<div style="white-space: nowrap">' . Html::activeTextInput($filterModel, 'priceMax', ['class' => 'form-control min price', 'placeholder' => 'max']) . ' <span class="price-format"></span>' . '</div>' .
					Html::activeDropDownList($filterModel, 'priceFilter', ['1' => 'Có giá', '0' => 'Thỏa thuận'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
			],
			['attribute' => 'facadeWidth', 'value' => 'adProductAdditionInfo.facade_width', 'filter' => Html::activeDropDownList($filterModel, 'facadeFilter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])],
			['attribute' => 'landWidth', 'value' => 'adProductAdditionInfo.land_width', 'filter' => Html::activeDropDownList($filterModel, 'landFilter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])],
			['attribute' => 'homeDirection', 'value' => 'adProductAdditionInfo.homeDirection', 'filter' => 
				Html::activeDropDownList($filterModel, 'homeDirection', $directionList, ['class' => 'form-control', 'prompt' => 'Tất cả']) .
				Html::activeDropDownList($filterModel, 'homeDirectionFilter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
			],
			['attribute' => 'facadeDirection', 'value' => 'adProductAdditionInfo.facadeDirection', 'filter' =>
				Html::activeDropDownList($filterModel, 'facadeDirection', $directionList, ['class' => 'form-control', 'prompt' => 'Tất cả']) .
				Html::activeDropDownList($filterModel, 'facadeDirectionFilter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
			],
			['format' => 'raw', 'attribute' => 'floor', 'value' => function($model){
    				return $model->adProductAdditionInfo->floor_no ? $model->adProductAdditionInfo->floor_no : null;
    			}, 'filter' =>
				Html::activeDropDownList($filterModel, 'floor', ['1' => '1+','2' => '2+','3' => '3+','4' => '4+','5' => '5+','6' => '6+'], ['class' => 'form-control', 'prompt' => 'Tất cả']) .
				Html::activeDropDownList($filterModel, 'floorFilter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
			],
			['format' => 'raw', 'attribute' => 'room', 'value' => function($model){
    				return $model->adProductAdditionInfo->room_no ? $model->adProductAdditionInfo->room_no : null;
    			}, 'filter' =>
				Html::activeDropDownList($filterModel, 'room', ['1' => '1+','2' => '2+','3' => '3+','4' => '4+','5' => '5+','6' => '6+'], ['class' => 'form-control', 'prompt' => 'Tất cả']) .
				Html::activeDropDownList($filterModel, 'roomFilter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
			],
			['format' => 'raw', 'attribute' => 'toilet', 'value' => function($model){
    				return $model->adProductAdditionInfo->toilet_no ? $model->adProductAdditionInfo->toilet_no : null;
    			}, 'filter' =>
				Html::activeDropDownList($filterModel, 'toilet', ['1' => '1+','2' => '2+','3' => '3+','4' => '4+','5' => '5+','6' => '6+'], ['class' => 'form-control', 'prompt' => 'Tất cả']) .
				Html::activeDropDownList($filterModel, 'toiletFilter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
			],
			['format' => 'raw', 'attribute' => 'interior', 'value' => function($model) { return $model->adProductAdditionInfo->interior ? mb_substr($model->adProductAdditionInfo->interior, 0, 20, 'UTF-8') . '...' : '<span style="color: red; font-style: italic;">[null]</span>'; },
				'filter' => Html::activeDropDownList($filterModel, 'interior', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
			],

			['attribute' => 'name', 'value' => 'adContactInfo.name',
				'filter' => Html::activeDropDownList($filterModel, 'nameFilter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
			],

			['attribute' => 'address', 'value' => function($model){
    				return $model->adContactInfo->address ? mb_substr($model->adContactInfo->address, 0, 20, 'UTF-8') . '...' : null;
    			},
			'filter' => Html::activeDropDownList($filterModel, 'addressFilter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
			],

			['attribute' => 'phone', 'value' => 'adContactInfo.phone',
			'filter' => Html::activeDropDownList($filterModel, 'phoneFilter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả'])
			],

			['attribute' => 'mobile', 'value' => 'adContactInfo.mobile',
			'filter' => Html::activeDropDownList($filterModel, 'mobileFilter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả']).
					Html::activeTextInput($filterModel, 'mobileSearch', ['class' => 'form-control'])
			],

			['attribute' => 'email', 'value' => 'adContactInfo.email',
			'filter' => Html::activeDropDownList($filterModel, 'emailFilter', ['1' => 'Có', '2' => 'Không'], ['class' => 'form-control', 'prompt' => 'Tất cả']) .
					Html::activeTextInput($filterModel, 'emailSearch', ['class' => 'form-control'])
			],
			'start_date:date',
            ['attribute' => 'file_name', 'format' => 'raw', 'value' => function($model){
                $file = \vsoft\craw\models\AdProductFile::find()->where(['file' => $model->file_name])->one();
                if(count($file) > 0) {
                    return Html::a($model->file_name, "#", [
                        'data-toggle' => "modal",
                        'data-target' => "#file-info",
                        'data-filename' => $file->file,
                        'data-filepath' => $file->path,
                        'data-imported' => date('d F Y', $file->imported_at),
                        'data-toolid' => $file->product_tool_id,
                        'data-copied' => $file->copied_at > 0 ? date('d F Y', $file->copied_at) : '',
                        'data-mainid' => $file->product_main_id > 0 ? Html::a(Yii::$app->urlManager->hostInfo. "/mv". $file->product_main_id, Yii::$app->urlManager->hostInfo. "/mv". $file->product_main_id, ['target' => 'blank']) : '',
                    ]);
                } else {
                    return $model->file_name;
                }
            }]
		],
    ]); ?>
    <?php if($import == 'true'){?>
    <a href="<?= Url::to(['/craw/manager/import', 'totalCount' => $dataProvider->totalCount, 'page' => 1, 'filter' => json_encode(Yii::$app->request->queryParams)]) ?>" class="btn btn-success">Import</a>
    <?php } ?>
</div>
<div id="file-info" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel">File Information</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">Name: </div>
                    <div class="col-md-8 filename"></div>
                </div>
                <div class="row">
                    <div class="col-md-4">Path: </div>
                    <div class="col-md-8 filepath"></div>
                </div>

                <div class="row">
                    <div class="col-md-4">Imported at: </div>
                    <div class="col-md-8 imported"></div>
                </div>
                <div class="row">
                    <div class="col-md-4">Crawl Product ID: </div>
                    <div class="col-md-8 toolid"></div>
                </div>

                <div class="row">
                    <div class="col-md-4">Copied at: </div>
                    <div class="col-md-8 copied"></div>
                </div>
                <div class="row">
                    <div class="col-md-4">Link product: </div>
                    <div class="col-md-8 mainid"></div>
                </div>
            </div>
        </div>
    </div>
</div>

