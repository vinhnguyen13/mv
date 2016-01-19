<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Yii::$app->name . (!empty($this->title) ? ' - '.Html::encode($this->title) : '') ?></title>
    <?php $this->head() ?>
    <script type="text/javascript">
    	window.onload = function(){
    		if (navigator.userAgent.indexOf('MSIE') !== -1 || navigator.appVersion.indexOf('Trident/') > 0) {
				var msie = navigator.userAgent.indexOf('MSIE'),
		    		ua = navigator.userAgent,
		    		getNumVersion = ua.substring (msie+5, ua.indexOf (".", msie ));

				if ( getNumVersion > 9 ) {
					var head  = document.getElementsByTagName('head')[0];
				    var link  = document.createElement('link');
				    link.rel  = 'stylesheet';
				    link.type = 'text/css';
				    link.href = '<?=Yii::$app->view->theme->baseUrl?>/resources/css/ie.css';
				    head.appendChild(link);		
				}else {
					document.getElementById('wrapper-body').style.display = 'none';
					document.getElementById('iePopup').style.display = 'block';
				}
				return;
			}       
        };
	</script>
</head>
<body>
    <?php $this->beginContent('@app/views/layouts/_partials/analyticstracking.php'); ?><?php $this->endContent();?>
    <?php $this->beginBody() ?>
        <?= $content ?>
    <?php $this->endBody() ?>
    <div id="iePopup">
	    <div id="jr_overlay"></div>
	    <div id="jr_wrap">
	        <div id="jr_inner">
	            <h1 id="jr_header">Bạn có biết rằng trình duyệt của bạn đã lỗi thời?</h1>
	            <p>Trình duyệt của bạn đã lỗi thời, và có thể không tương thích tốt với website, chắc chắn rằng trải nghiệm của bạn trên website sẽ bị hạn chế. Bên dưới là danh sách những trình duyệt phổ biến hiện nay.</p>
	            <p>Click vào biểu tượng để tải trình duyệt bạn muốn.</p>
	            <ul>
	                <li id="jr_chrome"><a href="http://www.google.com/chrome/" target="_blank">Chrome 34</a></li>
	                <li id="jr_firefox"><a href="http://www.mozilla.com/firefox/" target="_blank">Firefox 29</a></li>
	                <li id="jr_msie"><a href="http://www.microsoft.com/windows/Internet-explorer/" target="_blank">Internet Explorer 10</a></li>
	                <li id="jr_opera"><a href="http://www.opera.com/download/" target="_blank">Opera 20</a></li>
	                <li id="jr_safari"><a href="http://www.apple.com/safari/download/" target="_blank">Safari</a></li>
	            </ul>
	        </div>
	    </div>
	</div>
</body>
</html>
<?php $this->endPage() ?>