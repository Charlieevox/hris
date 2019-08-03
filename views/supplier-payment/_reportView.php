<?php
use app\models\MsCompany;

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

<?php $this->beginBody() ?>
<div class="wrap" style="font-size: 12px !important;">
    <div>
        <div style="text-align: center; font-size: 30px; color: Blue;">EASYB WEB</div>
        <div style="text-align: center; font-size: 21px; border-top: #000 double medium; border-bottom: #000 double medium;">EasyB for Service Industry </div>

        <div style="margin-top: 10px;">
            <div style="float:left; width: 70%;">
                <div style="font-size: 10px !important;"><i>Company :</i></div>
                <div><b>
                    <?php 
                    $ID = Yii::$app->user->identity->companyID;
                    $modelcompanyName = MsCompany::findOne($ID);
                    $companyName = $modelcompanyName->companyName;                  
                ?>
                <?= $companyName ?></b></div>
            </div>
            <div style="clear: both;"></div>
        </div>

        <div style="margin-top: 10px; text-align: center; font-size: 16px;"><b>SUPPLIER PAYMENT REPORT</b></div>

        <div style="margin-top: 10px; padding: 10px 10px 10px 10px;">
            <div style="float:left; width: 70%;">
               <div>
                    <div style="width: 115px; float: left;">PAYMENT NUMBER</div>
                    <div style="display:block; float: left;">: <?= $model->paymentNum ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div>
                    <div style="width: 115px; float: left;">DATE</div>
                    <div style="display:block; float: left;">: <?= $model->paymentDate ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div>
                    <div style="width: 115px; float: left;">CASH ACCOUNT</div>
                    <div style="display:block; float: left;">: <?= $model->coaNos->description ?></div>
                    <div style="clear: both;"></div>
                </div>
            </div>
            <div style="float:left; width: 30%;">
                <div><b>VENDOR</b></div>
                <div><?= $model->supplier->supplierName ?></div>   
            </div>
            <div style="clear: both;"></div>
        </div>

        <div style="padding: 10px 10px 10px 10px;">
            <div style="margin-top: 10px;"><b><i>SUPPLIER PAYMENT DETAIL</i></b></div>
                <div style="border-bottom: #000 solid 1px; margin: 10px 1% 0px 1%; padding-bottom: 10px;">
                    <div style="float:left; width: 5%; text-align: center;">No.</div>
                    <div style="float:left; width: 31%; text-align: left;">Purchase Number</div>
                    <div style="float:left; width: 31%; text-align: center;">Due Date</div>
                    <div style="float:left; width: 31%; text-align: right;">Payment Total</div>
                    <div style="clear: both;"></div>
                </div>
            
                <?php 
                    $i = 1;
                    foreach ($model->joinSupplierPaymentDetail as $row) {
                        ?>
                        <div style="margin: 5px 1%; ">
                            <div style="float:left; width: 5%; text-align: center;"><?= $i ?></div>
                            <div style="float:left; width: 31%;"><?= $row['purchaseNum'] ?>&nbsp;</div>
                            <div style="float:left; width: 31%; text-align: center;"><?= $row['dueDate'] ?>&nbsp;</div>
                            <div style="float:left; width: 31%; text-align: right;"><?= number_format($row['paymentTotal'], 2,",",".") ?></div>
                            <div style="clear: both;"></div>
                        </div>
                        <?php 
                        $i = $i + 1;
                    }
                ?>
                <br>
                    <div style="float:left; width: 60%;">
                       <div>
                            <div style="width: 150px; float: left;">ADDITIONAL INFORMATION</div>
                            <div style="display:block; float: left;">: <?= $model->additionalInfo ?></div>
                        </div>                            
                        <div style="clear: both;"></div>

                    </div>
                    <div style="float:left; width: 40%;">
                        <div style="float:left; width: 50%;"><b>GRAND TOTAL</b></div>
                        <div style="float:left; width: 50%; text-align: right;">Rp. <?= number_format($model->grandTotal, 2,",",".") ?></div>
                    </div>
                    <div style="clear: both;"></div>
                <br>
    </div>
</div>
    
<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>