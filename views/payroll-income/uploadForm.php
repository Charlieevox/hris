<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
?>

<div class="panel">
    <div class="panel-heading">
        <div class="panel-title">
            <?php echo Yii::t('app', 'Upload Income') ?>
        </div>
    </div>
    <?php
    $form = ActiveForm::begin([
                'enableAjaxValidation' => true,
                'type' => ActiveForm::TYPE_HORIZONTAL,
                'options' => [
                    'enctype' => 'multipart/form-data',
                ],
    ]);
    ?>
    <div class="control-panel-body panel-body">
        <?=
                $form->field($model, 'file')
                ->widget(\kartik\file\FileInput::classname(), [
                    'options' => [
                        'multiple' => false,
                    ],
                    'name' => 'attachment_50',
                    'pluginOptions' => [
                        'showPreview' => false,
                        'showCaption' => true,
                        'showRemove' => true,
                        'showUpload' => false
                    ]
                ])
        ?>
    </div>
</div>
<div class="panel greeting">
    <div class="panel-body">
        <div class="text-right">
            <?= Html::submitButton('Upload', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Kembali', ['index'], ['class' => 'btn btn-danger']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>


