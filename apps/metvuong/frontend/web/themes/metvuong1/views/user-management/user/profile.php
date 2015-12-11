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
                    <div class="alert alert-info">
                        Credentials will be sent to the user by email.
                        A password will be generated automatically if not provided.
                    </div>
                    <form id="w3" class="form-horizontal" action="/admin/user/admin/create" method="post" role="form">
                        <input type="hidden" name="_csrf" value="eEQyMm9WaUUVPUt5IQ4BLhsVRXYgPTFyPCJ4XwhlMzIRHlNhVmcjHQ==">

                        <div class="form-group field-user-email required has-error">
                            <label class="control-label col-sm-3" for="user-email">Email</label>
                            <div class="col-sm-9">
                                <input type="text" id="user-email" class="form-control" name="User[email]" maxlength="255">
                                <div class="help-block help-block-error ">Email cannot be blank.</div>
                            </div>

                        </div><div class="form-group field-user-username required has-error">
                            <label class="control-label col-sm-3" for="user-username">Username</label>
                            <div class="col-sm-9">
                                <input type="text" id="user-username" class="form-control" name="User[username]" maxlength="255">
                                <div class="help-block help-block-error ">This username has already been taken</div>
                            </div>

                        </div><div class="form-group field-user-password has-success">
                            <label class="control-label col-sm-3" for="user-password">Password</label>
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
