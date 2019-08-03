<?php

use app\models\TrPayrollProc;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
//AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
    <head>
        <?php $this->head() ?>
    </head>
    <body>

        <div class="wrap" style="font-size: 12px !important;">
            <div>
                <div style="text-align: center; font-size: 30px; color: Blue;">EASYB WEB</div>
                <div style="text-align: center; font-size: 21px; border-top: #000 double medium; border-bottom: #000 double medium;">EasyB for Service Industry </div>

                <div style="margin-top: 10px;">
                    <div style="float:left; width: 70%;">
                        <div style="font-size: 10px !important;"><i>Company :</i></div>
                        <div></div>
                    </div>
                    <div style="clear: both;"></div>
                </div>

                <div style="margin-top: 10px; text-align: center; font-size: 16px;"><b>Profile</b></div>

                <div style="float:left; width: 50%;"><b>Information</b>
                    <br>
                    <br>
                    <div>
                        <div style="width: 150px; float: left;"><?= Html::activeLabel($model, 'firstName'); ?></div>
                        <div style="display:block; float: left;">: <?= $model->firstName ?></div>
                        <div style="width: 150px; float: left;"><?= Html::activeLabel($model, 'lastName'); ?></div>
                        <div style="display:block; float: left;">: <?= $model->lastName ?></div>
                        <div style="width: 150px; float: left;"><?= Html::activeLabel($model, 'birthPlace'); ?></div>
                        <div style="display:block; float: left;">: <?= $model->birthPlace ?></div>
                        <div style="width: 150px; float: left;"><?= Html::activeLabel($model, 'birthPlace'); ?></div>
                        <div style="display:block; float: left;">: <?= $model->birthDate ?></div>
                        <div style="width: 150px; float: left;"><?= Html::activeLabel($model, 'address'); ?></div>
                        <div style="display:block; float: left;">: <?= $model->address ?></div>
                        <div style="width: 150px; float: left;"><?= Html::activeLabel($model, 'city'); ?></div>
                        <div style="display:block; float: left;">: <?= $model->city ?></div>
                    </div>                            
                    <div style="clear: both;"></div>

                </div>
                <div style="float:left; width: 50%;">
                    <br>
                    <br>
                    <div style="width: 150px; float: left;"><?= Html::activeLabel($model, 'email'); ?></div>
                    <div style="display:block; float: left;">: <?= $model->email ?></div>
                    <div style="width: 150px; float: left;"><?= Html::activeLabel($model, 'gender'); ?></div>
                    <div style="display:block; float: left;">: <?= $model->genderdesc->description ?></div>
                    <div style="width: 150px; float: left;"><?= Html::activeLabel($model, 'education'); ?></div>
                    <div style="display:block; float: left;">: <?= $model->education ?></div>
                    <div style="width: 150px; float: left;"><?= Html::activeLabel($model, 'position'); ?></div>
                    <div style="display:block; float: left;">: <?= $model->positiondesc->positionName ?></div>
                    <div style="width: 150px; float: left;"><?= Html::activeLabel($model, 'divisionId'); ?></div>
                    <div style="display:block; float: left;">: <?= $model->divisionId ?></div>
                    <div style="width: 150px; float: left;"><?= Html::activeLabel($model, 'departmentId'); ?></div>
                    <div style="display:block; float: left;">: <?= $model->department->departmentDesc ?></div>
                </div>
                <div style="clear: both;"></div>
                <br>
                <div style="border-bottom: #000 solid 1px; padding-bottom: 10px;"></div>

                <div style="float:left; width: 50%;"><b>Calculation</b>
                    <br>
                    <br>
                    <div>
                        <div style="width: 150px; float: left;"><?= Html::activeLabel($model, 'startPayroll'); ?></div>
                        <div style="display:block; float: left;">: <?= $model->startPayroll ?></div>
                        <div style="width: 150px; float: left;"><?= Html::activeLabel($model, 'endPayroll'); ?></div>
                        <div style="display:block; float: left;">: <?= $model->endPayroll ?></div>
                        <div style="width: 150px; float: left;"><?= Html::activeLabel($model, 'maritalStatus'); ?></div>
                        <div style="display:block; float: left;">: <?= $model->maritalstatusdesc->key2 ?></div>
                        <div style="width: 150px; float: left;"><?= Html::activeLabel($model, 'dependent'); ?></div>
                        <div style="display:block; float: left;">: <?= $model->dependent ?></div>
                        <div style="width: 150px; float: left;"><?= Html::activeLabel($model, 'jamsostekParm'); ?></div>
                        <div style="display:block; float: left;">: <?= $model->jamsostekParm ?></div>
                        <div style="width: 150px; float: left;"><?= Html::activeLabel($model, 'bpjskNo'); ?></div>
                        <div style="display:block; float: left;">: <?= $model->bpjskNo ?></div>
                        <div style="width: 150px; float: left;"><?= Html::activeLabel($model, 'bpkstkNo'); ?></div>
                        <div style="display:block; float: left;">: <?= $model->bpkstkNo ?></div>                        
                    </div>                            
                    <div style="clear: both;"></div>

                </div>
                <div style="float:left; width: 50%;">
                    <br>
                    <br>
                    <div style="width: 150px; float: left;"><?= Html::activeLabel($model, 'bankName'); ?></div>
                    <div style="display:block; float: left;">: <?= $model->bankName ?></div>
                    <div style="width: 150px; float: left;"><?= Html::activeLabel($model, 'branch'); ?></div>
                    <div style="display:block; float: left;">: <?= $model->branch ?></div>
                    <div style="width: 150px; float: left;"><?= Html::activeLabel($model, 'bankNo'); ?></div>
                    <div style="display:block; float: left;">: <?= $model->bankNo ?></div>
                </div>


                <?php $this->endBody() ?>
            </div>
        </div>
    </body>

</html>
<?php $this->endPage() ?>