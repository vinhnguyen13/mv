<?php
$env = YII_ENV;
if(!empty($env) && in_array($env, [YII_ENV_PROD, YII_ENV_TEST])){
?>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-71233207-1', 'auto');
    ga('send', 'pageview');

    ga('create', 'UA-71563942-1', 'auto', {'name':'metvuong'});
    ga('metvuong.send', 'pageview');

</script>
<?php
}
if(true){
    $parseUrl = Yii::$app->urlManager->parseRequest(Yii::$app->request);
    $urlBase = !empty($parseUrl[0]) ? $parseUrl[0] : '';


?>
    <script type="text/javascript">
        $(document).ready(function(){
            if (typeof ga !== "undefined") {
                <?php if(!Yii::$app->user->isGuest){?>
                    ga('send', {
                        hitType: 'event',
                        eventCategory: '<?=Yii::$app->user->identity->username;?>',
                        eventAction: '<?=\yii\helpers\Url::current();?>',
                        eventLabel: '<?=\yii\helpers\Url::current();?>'
                    });
                <?php }else{
                ?>
                    ga('send', {
                        hitType: 'event',
                        eventCategory: 'Guest <?=Yii::$app->request->userIP;?>',
                        eventAction: '<?=\yii\helpers\Url::current();?>',
                        eventLabel: '<?=\yii\helpers\Url::current();?>'
                    });
                <?php
                }
                ?>
            }
        });
    </script>
<?php
}
?>