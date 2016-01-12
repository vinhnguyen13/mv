<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <a href="#" class="logo-footer"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/logo.png" alt=""></a>
            </div>
            <div class="col-md-3">
                <ul>
                    <li><a href="#">home</a></li>
                    <li><a href="#">pricing</a></li>
                    <li><a href="#">new developments</a></li>
                    <li><a href="#">area guides</a></li>
                    <li><a href="#">market insight</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <ul>
                    <li><a href="#">about us</a></li>
                    <li><a href="#">contact us</a></li>
                    <li><a href="#">met vuong evaluator</a></li>
                    <li><a href="#">mortgage calculator</a></li>
                    <li><a href="#">careers</a></li>
                </ul>
            </div>
            <div class="col-md-3 send-email">
                <p>subscribe to our newsletter</p>
                <form action="">
                    <input type="text" placeholder="Email address">
                    <button class="btn-submit-email"><span class="icon"></span></button>
                </form>
            </div>
        </div>
        <div class="pull-right">&copy; 2015 Met Vuong. All rights reserved</div>
    </div>
</footer>
<?php $this->beginContent('@app/views/layouts/_partials/popup.php'); ?><?php $this->endContent();?>