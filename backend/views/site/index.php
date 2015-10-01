<?php
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Administrator!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Development</h2>
                <p>Start the fun with the following code generators</p>
                <ol class="list-unstyled">
                    <li><a href="<?=Yii::$app->urlManager->createUrl(['gii', 'id' => 0])?>">Gii</a></li>
                    <li><a href="<?=Yii::$app->urlManager->createUrl(['setting'])?>">Setting</a></li>
                    <li><a href="<?=Yii::$app->urlManager->createUrl(['translatemanager/language/list'])?>">Translate</a></li>
                </ol>
            </div>

            <div class="col-lg-4">
                <h2>CMS</h2>
                <p>Content management systems</p>
                <ol class="list-unstyled">
                    <li><a href="<?=Yii::$app->urlManager->createUrl(['cms/cms-show'])?>">Content</a></li>
                    <li><a href="<?=Yii::$app->urlManager->createUrl(['cms/cms-catalog'])?>">Categories</a></li>
                    <li><a href="<?=Yii::$app->urlManager->createUrl(['gallery'])?>">Gallery</a></li>
                </ol>
            </div>


            <div class="col-lg-4">
                <h2>User Management</h2>
                <p>Yii2 User Management</p>
                <ol class="list-unstyled">
                    <li><a href="<?=Yii::$app->urlManager->createUrl(['user/admin'])?>">User</a></li>
                    <li><a href="<?=Yii::$app->urlManager->createUrl(['admin'])?>">User ACL</a></li>
                </ol>
            </div>

        </div>
    </div>
</div>
