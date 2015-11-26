<?php
namespace backend\components;

use yii\web\View;
use yii\helpers\ArrayHelper;
class Sort {
	private static $count = 0;
	private $models;
	private $sortByAttribute;
	private $options = [
		'label' => 'name',
		'id' => 'id',
	];

	public $prefixId = 'sort';
	public $sortHiddenId = '';
	
	public function __construct($models, $sortByAttribute = 'order') {
		$this->models = $models;
		$this->sortByAttribute = $sortByAttribute;
		
		$this->prefixId = $this->prefixId . self::$count;
		$this->sortHiddenId = $this->prefixId . '-hidden';
		
		self::$count += 1;
	}
	
	public function render($options = []) {
		$options = array_merge($this->options, $options);
		
		$sortParentId = $this->prefixId . '-parent';
		
		$html = '<input type="hidden" id="' . $this->sortHiddenId . '" name="' . $this->sortHiddenId . '" />';
		$html .= '<ul id="' . $sortParentId . '">';
		
		foreach ($this->models as $model) {
			$labelAttribute = $options['label'];
			$idAttribute = $options['id'];
			
			$html .= '<li class="ui-state-default" id="' . $this->prefixId . '-' . $model->$idAttribute . '"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>' . $model->$labelAttribute . '</li>';
		}
		
		$html .= '</ul>';
		
		$script = <<<EOD
			$("#$sortParentId").sortable({
				create: function() {
					$('#$this->sortHiddenId').val($(this).sortable('serialize'));
				},
				update: function(event, ui) {
					$('#$this->sortHiddenId').val($(this).sortable('serialize'));
				}
			});
EOD;
		
		\Yii::$app->view->registerJsFile(\Yii::getAlias('@web') . '/js/jquery-ui.min.js', ['depends' => ['yii\web\YiiAsset']]);
		\Yii::$app->view->registerCssFile(\Yii::getAlias('@web') . '/css/jquery-ui.css', ['depends' => ['yii\web\YiiAsset']]);
		\Yii::$app->view->registerJs($script, View::POS_READY);
		
		return $html;
	}
	
	public function save($data) {
		parse_str($data[$this->sortHiddenId]);
		$postName = $this->prefixId;
		$sort = $$postName;
		$models = ArrayHelper::index($this->models, $this->options['id']);
		$sortAtribute = $this->sortByAttribute;
		foreach ($sort as $order => $id) {
			$model = $models[$id];
			$model->$sortAtribute = $order;
			$model->save();
		}
	}
}