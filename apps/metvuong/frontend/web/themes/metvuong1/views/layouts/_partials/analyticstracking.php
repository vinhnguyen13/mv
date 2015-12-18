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

</script>
<?php
}
?>