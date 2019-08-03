<?php
	use yii\bootstrap\ActiveForm;
	use yii\helpers\Html;
	
	$this->title = 'Login';
?>

<style type="text/css">
    .checkbox label {
        position: relative !important;
    }
    
    .checkbox label input[type="checkbox"] {
        top: -1px !important;
    }
</style>

<div class="site-login">
    <div class="row">
        <div class="col-md-6  col-md-offset-1">
            <div class="panel panel-primary" id="panel-banner">
                <div class="panel-body">
                    <div class="text-center">
<!--                        <asp:Image ID="imgLogo" ImageUrl="~/Images/logo.png" runat="server" />-->
                        <h2>
                            <?= Html::encode($this->title) ?>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary" id="panel-login">
                <div class="panel-heading">
                    <h3 class="panel-title">Please Sign In</h3>
                </div>
                
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                ]); ?>
                
                <div class="panel-body">
                    <div class="form-horizontal" style="margin: 20px;">
                        
                        <?= $form->field($model, 'username', [
                            'template' => "<div class=\"margin-bottom input-group input-group-lg\"><span class=\"input-group-addon\"><i class=\"fa fa-user\"></i></span>{input}</div>\n{hint}\n{error}",
                        ])->textInput(array('placeholder' => 'Username')) ?>
                        <?= $form->field($model, 'password', [
                            'template' => "<div class=\"margin-bottom input-group input-group-lg\"><span class=\"input-group-addon\"><i class=\"fa fa-lock\"></i></span>{input}</div>\n{hint}\n{error}",
                        ])->passwordInput(array('placeholder' => 'Password')) ?>
                        <?= $form->field($model, 'rememberMe')->checkbox() ?>
                        
                        <div class="pull-right">
                            <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>