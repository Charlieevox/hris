<?php
use app\models\MsCompany;
use yii\db\Expression;

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

        <div style="margin-top: 10px; text-align: center; font-size: 16px;"><b>AP AGING REPORT</b></div>

        <div style="margin-top: 10px; padding: 10px 10px 10px 10px;">
            <div style="float:left; width: 70%;">
                <div>
                    <div style="width: 115px; float: left;">AP AGING DATE</div>
                    <div style="display:block; float: left;">: <?= $model->payableDate ?></div>
                    <div style="clear: both;"></div>
                </div>
            </div>
            
            <div style="clear: both;"></div>
        </div>

        <div style="padding: 10px 10px 10px 10px;">
                <div style="border-bottom: #000 solid 1px; margin: 10px 1% 0px 1%; padding-bottom: 10px;">
                    <div style="float:left; width: 5%; text-align: center;">No.</div>
                    <div style="float:left; width: 20%; text-align: left;">Supplier Name</div>
                    <div style="float:left; width: 11%; text-align: right;">Current</div>
                    <div style="float:left; width: 11%; text-align: right;">1 - 30</div>
                    <div style="float:left; width: 11%; text-align: right;">31 - 60</div>
                    <div style="float:left; width: 11%; text-align: right;">61 - 90</div>
                    <div style="float:left; width: 11%; text-align: right;">> 90</div>
                    <div style="float:left; width: 18%; text-align: right;">Total</div>
                    <div style="clear: both;"></div>
                </div>
            
                <?php 
                
                    $connection = Yii::$app->db;
                    $sql = "SELECT a.supplierID, a.supplierName, IFNULL(b.currentAR,0.00) AS currentAR, 
					IFNULL(c.monthAR,0.00) AS monthAR, IFNULL(d.twoMonth,0.00) AS twoMonth, 
					IFNULL(e.threeMonth,0.00) AS threeMonth, IFNULL(f.mustThreeMonth,0.00) AS mustThreeMonth,
					(IFNULL(b.currentAR,0.00) + IFNULL(c.monthAR,0.00) + IFNULL(d.twoMonth,0.00) +
					 IFNULL(e.threeMonth,0.00) + IFNULL(f.mustThreeMonth,0.00)) AS Total
					FROM ms_supplier a
					LEFT JOIN
					(
						SELECT b.supplierID, SUM(b.grandTotal) - IFNULL(SUM(d.grandTotal),0)  AS currentAR
						FROM ms_supplier a
						JOIN tr_purchaseorderhead b on a.supplierID = b.supplierID
						LEFT JOIN tr_supplierpaymentdetail c on b.purchaseNum = c.purchaseNum
						LEFT JOIN tr_supplierpaymenthead d on c.paymentNum = d.paymentNum
						WHERE DATEDIFF(b.dueDate,NOW()) < 1 
						GROUP BY b.supplierID 
					)b on a.supplierID = b.supplierID
					LEFT JOIN
					(
						SELECT b.supplierID, SUM(b.grandTotal) - IFNULL(SUM(d.grandTotal),0)  AS monthAR
						FROM ms_supplier a
						JOIN tr_purchaseorderhead b on a.supplierID = b.supplierID
						LEFT JOIN tr_supplierpaymentdetail c on b.purchaseNum = c.purchaseNum
						LEFT JOIN tr_supplierpaymenthead d on c.paymentNum = d.paymentNum
						WHERE DATEDIFF(b.dueDate,NOW()) < 30 AND DATEDIFF(b.dueDate,NOW()) > 1 
						GROUP BY b.supplierID 
					)c on a.supplierID = c.supplierID
					LEFT JOIN
					(
						SELECT b.supplierID, SUM(b.grandTotal) - IFNULL(SUM(d.grandTotal),0) AS twoMonth
						FROM ms_supplier a
						JOIN tr_purchaseorderhead b on a.supplierID = b.supplierID
						LEFT JOIN tr_supplierpaymentdetail c on b.purchaseNum = c.purchaseNum
						LEFT JOIN tr_supplierpaymenthead d on c.paymentNum = d.paymentNum
						WHERE DATEDIFF(b.dueDate,NOW()) < 60 AND DATEDIFF(b.dueDate,NOW()) > 30 
						GROUP BY b.supplierID 
					)d on a.supplierID = d.supplierID
					LEFT JOIN
					(
						SELECT b.supplierID, SUM(b.grandTotal) - IFNULL(SUM(d.grandTotal),0)  AS threeMonth
						FROM ms_supplier a
						JOIN tr_purchaseorderhead b on a.supplierID = b.supplierID
						LEFT JOIN tr_supplierpaymentdetail c on b.purchaseNum = c.purchaseNum
						LEFT JOIN tr_supplierpaymenthead d on c.paymentNum = d.paymentNum
						WHERE DATEDIFF(b.dueDate,NOW()) < 90 AND DATEDIFF(b.dueDate,NOW()) > 60 
						GROUP BY b.supplierID 
					)e on a.supplierID = e.supplierID
					LEFT JOIN
					(
						SELECT b.supplierID, SUM(b.grandTotal) - IFNULL(SUM(d.grandTotal),0)  AS mustThreeMonth
						FROM ms_supplier a
						JOIN tr_purchaseorderhead b on a.supplierID = b.supplierID
						LEFT JOIN tr_supplierpaymentdetail c on b.purchaseNum = c.purchaseNum
						LEFT JOIN tr_supplierpaymenthead d on c.paymentNum = d.paymentNum
						WHERE DATEDIFF(b.dueDate,NOW()) > 90 
						GROUP BY b.supplierID 
					)f on a.supplierID = f.supplierID
                                        WHERE a.supplierID IN (select supplierID FROM tr_purchaseorderhead)
					GROUP BY a.supplierID ";
                    $temp = $connection->createCommand($sql);
//                    echo"<pre>";
//                    var_dump($temp);
//                    echo"</pre>";
//                    yii::$app->end();
                    $headResult = $temp->queryAll();
                
                    $i = 1;
                    foreach ($headResult as $row) {
                        ?>
                        <div style="margin: 5px 1%; ">
                            <div style="float:left; width: 5%; text-align: center; font-size: 9px;"><?= $i ?></div>
                            <div style="float:left; width: 20%; font-size: 10px; font-size: 9px;"><?= $row['supplierName'] ?>&nbsp;</div>
                            <div style="float:left; width: 11%; text-align: right; font-size: 9px;"><?= number_format($row['currentAR'], 2,",",".") ?></div>
                            <div style="float:left; width: 11%; text-align: right; font-size: 9px;"><?= number_format($row['monthAR'], 2,",",".") ?></div>
                            <div style="float:left; width: 11%; text-align: right; font-size: 9px;"><?= number_format($row['twoMonth'], 2,",",".") ?></div>
                            <div style="float:left; width: 11%; text-align: right; font-size: 9px;"><?= number_format($row['threeMonth'], 2,",",".") ?></div>
                            <div style="float:left; width: 11%; text-align: right; font-size: 9px;"><?= number_format($row['mustThreeMonth'], 2,",",".") ?></div>
                            <div style="float:left; width: 18%; text-align: right; font-size: 9px;"><?= number_format($row['Total'], 2,",",".") ?></div>
                            <div style="clear: both;"></div>
                        </div>
                        <?php 
                        $i = $i + 1;
                    }
                ?>
               
    </div>
</div>
    
<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
