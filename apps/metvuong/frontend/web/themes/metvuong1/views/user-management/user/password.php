<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/11/2015
 * Time: 2:34 PM
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use frontend\models\LoginForm;

$model = Yii::createObject(LoginForm::className());
?>
<div class="col-xs-9 right-profile quanlytinraoban">
    <div class="wrap-quanly-profile">
        <div class="title-frm">Cập nhật thông tin của bạn</div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form id="w3" class="form-horizontal" action="/admin/user/admin/create" method="post" role="form">
                        <input type="hidden" name="_csrf" value="eEQyMm9WaUUVPUt5IQ4BLhsVRXYgPTFyPCJ4XwhlMzIRHlNhVmcjHQ==">

                        <div class="form-group field-user-password has-success">
                            <label class="control-label col-sm-3" for="user-password">Password Cũ</label>
                            <div class="col-sm-9">
                                <input type="password" id="user-password" class="form-control" name="User[password]">
                                <div class="help-block help-block-error "></div>
                            </div>

                        </div>
                        <div class="form-group field-user-password has-success">
                            <label class="control-label col-sm-3" for="user-password">Password Mới</label>
                            <div class="col-sm-9">
                                <input type="password" id="user-password" class="form-control" name="User[password]">
                                <div class="help-block help-block-error "></div>
                            </div>

                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-3 col-lg-9">
                                <button type="submit" class="btn btn-block btn-success">Save</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
