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

        <div style="margin-top: 10px; text-align: center; font-size: 16px;"><b>INVOICE REPORT</b></div>

        <div style="margin-top: 10px; padding: 10px 10px 10px 10px;">
            <div style="float:left; width: 60%;">
               <div>
                    <div style="width: 115px; float: left;">INVOICE NUMBER</div>
                    <div style="display:block; float: left;">: <?= $model->salesNum ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div>
                    <div style="width: 115px; float: left;">DATE</div>
                    <div style="display:block; float: left;">: <?= $model->salesDate ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div>
                    <div style="width: 115px; float: left;">DUE DATE</div>
                    <div style="display:block; float: left;">: <?= $model->dueDate ?></div>
                    <div style="clear: both;"></div>
                </div>
            </div>
            <div style="float:left; width: 40%;">
                <div>
                    <div style="width: 100px; float: left;">CLIENT</div>
                    <div style="display:block; float: left;">: <?= $model->client->clientName ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div>
                    <div style="width: 100px; float: left;">PROJECT NAME</div>
                    <div style="display:block; float: left;">: <?= $model->projectNames ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div>
                    <div style="width: 100px; float: left;">BILLING DATE</div>
                    <div style="display:block; float: left;">: <?= $model->billingDate ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div>
                    <div style="width: 100px; float: left;">BILLING TOTAL</div>
                    <div style="display:block; float: left;">: <?= number_format($model->billingTotal, 2,",",".") ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div>
                    <div style="width: 100px; float: left;">PAYMENT TOTAL</div>
                    <div style="display:block; float: left;">: <?= number_format($model->paymentTotal, 2,",",".") ?></div>
                    <div style="clear: both;"></div>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
        
        <div style="padding: 10px 10px 10px 10px;">
            <div style="margin-top: 10px;"><b><i>INVOICE DETAIL</i></b></div>
                <div style="border-bottom: #000 solid 1px; margin: 10px 1% 0px 1%; padding-bottom: 10px;">
                    <div style="float:left; width: 5%; text-align: center; font-size: 10px;">No.</div>
                    <div style="float:left; width: 15%; text-align: left; font-size: 10px;">Product Name</div>
                    <div style="float:left; width: 5%; text-align: center; font-size: 10px;">Unit</div>
                    <div style="float:left; width: 5%; text-align: right; font-size: 10px;">Qty</div>
                    <div style="float:left; width: 15%; text-align: right; font-size: 10px;">Price</div>
                    <div style="float:left; width: 10%; text-align: right; font-size: 10px;">Discount</div>
                    <div style="float:left; width: 10%; text-align: right; font-size: 10px;">Tax</div>
                    <div style="float:left; width: 15%; text-align: right; font-size: 10px;">Outstanding</div>
                    <div style="float:left; width: 19%; text-align: right; font-size: 10px;">Sub Total</div>
                    <div style="clear: both;"></div>
                </div>
            
                <?php 
                    $i = 1;
                    foreach ($model->joinSalesOrderDetail as $row) {
                        ?>
                        <div style="margin: 5px 1%; ">
                            <div style="float:left; width: 5%; text-align: center; font-size: 10px;"><?= $i ?></div>
                            <div style="float:left; width: 15%; font-size: 10px;"><?= $row['productName'] ?>&nbsp;</div>
                            <div style="float:left; width: 5%; text-align: center; font-size: 10px;"><?= $row['uomName'] ?>&nbsp;</div>
                            <div style="float:left; width: 5%; text-align: right; font-size: 10px;"><?= number_format($row['qty'], 2,",",".") ?></div>
                            <div style="float:left; width: 15%; text-align: right; font-size: 10px;"><?= number_format($row['price'], 2,",",".") ?></div>
                            <div style="float:left; width: 10%; text-align: right; font-size: 10px;"><?= number_format($row['discount'], 2,",",".") ?> % </div>
                            <div style="float:left; width: 10%; text-align: right; font-size: 10px;"><?= number_format($row['taxValue'], 2,",",".") ?> % </div>
                            <div style="float:left; width: 15%; text-align: right; font-size: 10px;"><?= number_format($row['outstanding'], 2,",",".") ?></div>
                            <div style="float:left; width: 19%; text-align: right; font-size: 10px;"><?= number_format($row['subTotal'], 2,",",".") ?></div>
                            <div style="clear: both;"></div>
                        </div>
                        <?php 
                        $i = $i + 1;
                    }
                ?>
                <?php 
                    $sumPayment = 0;
                    $sumtax = 0;
                    foreach ($model->joinSalesOrderDetail as $row) {
                        $sumPayment = $sumPayment + $row['subTotal'];
                        $sumtax = $sumtax + ($row['subTotal'] * $row['taxValue']/100);
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
                        <div style="float:left; width: 50%;"><b>SUB TOTAL</b></div>
                        <div style="float:left; width: 50%; text-align: right;">Rp. <?= number_format($sumPayment, 2,",",".") ?></div>
                        <div style="float:left; width: 50%;"><b>TAX TOTAL</b></div>
                        <div style="float:left; width: 50%; text-align: right;">Rp. <?= number_format($sumtax, 2,",",".") ?></div>
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
