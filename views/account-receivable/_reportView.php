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

        <div style="margin-top: 10px; text-align: center; font-size: 16px;"><b>AR AGING REPORT</b></div>

        <div style="margin-top: 10px; padding: 10px 10px 10px 10px;">
            <div style="float:left; width: 70%;">
                <div>
                    <div style="width: 115px; float: left;">AR AGING DATE</div>
                    <div style="display:block; float: left;">: <?= $model->receivableDate ?></div>
                    <div style="clear: both;"></div>
                </div>
            </div>
            
            <div style="clear: both;"></div>
        </div>

        <div style="padding: 10px 10px 10px 10px;">
                <div style="border-bottom: #000 solid 1px; margin: 10px 1% 0px 1%; padding-bottom: 10px;">
                    <div style="float:left; width: 5%; text-align: center;">No.</div>
                    <div style="float:left; width: 20%; text-align: left;">Client Name</div>
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
                    $sql = "SELECT a.clientID, a.clientName, IFNULL(b.currentAR,0) AS currentAR, 
					IFNULL(c.monthAR,0) AS monthAR, IFNULL(d.twoMonth,0) AS twoMonth, 
					IFNULL(e.threeMonth,0) AS threeMonth, IFNULL(f.mustThreeMonth,0) AS mustThreeMonth,
					(IFNULL(b.currentAR,0) + IFNULL(c.monthAR,0) + IFNULL(d.twoMonth,0) +
					IFNULL(e.threeMonth,0) + IFNULL(f.mustThreeMonth,0)) AS Total
					FROM ms_client a
					LEFT JOIN
					(
						SELECT b.clientID, SUM(b.grandTotal) - IFNULL(SUM(d.grandTotal),0)  AS currentAR
						FROM ms_client a
						JOIN tr_salesorderhead b on a.clientID = b.clientID
						LEFT JOIN tr_clientsettlementdetail c on b.salesNum = c.salesNum
						LEFT JOIN tr_clientsettlementhead d on c.settlementNum = d.settlementNum
						WHERE DATEDIFF(b.dueDate,NOW()) < 1 
						GROUP BY b.clientID 
					)b on a.clientID = b.clientID
					LEFT JOIN
					(
						SELECT b.clientID, SUM(b.grandTotal) - IFNULL(SUM(d.grandTotal),0)  AS monthAR
						FROM ms_client a
						JOIN tr_salesorderhead b on a.clientID = b.clientID
						LEFT JOIN tr_clientsettlementdetail c on b.salesNum = c.salesNum
						LEFT JOIN tr_clientsettlementhead d on c.settlementNum = d.settlementNum
						WHERE DATEDIFF(b.dueDate,NOW()) < 30 AND DATEDIFF(b.dueDate,NOW()) > 1 
						GROUP BY b.clientID 
					)c on a.clientID = c.clientID
					LEFT JOIN
					(
						SELECT b.clientID, SUM(b.grandTotal) - IFNULL(SUM(d.grandTotal),0) AS twoMonth
						FROM ms_client a
						JOIN tr_salesorderhead b on a.clientID = b.clientID
						LEFT JOIN tr_clientsettlementdetail c on b.salesNum = c.salesNum
						LEFT JOIN tr_clientsettlementhead d on c.settlementNum = d.settlementNum
						WHERE DATEDIFF(b.dueDate,NOW()) < 60 AND DATEDIFF(b.dueDate,NOW()) > 30 
						GROUP BY b.clientID 
					)d on a.clientID = d.clientID
					LEFT JOIN
					(
						SELECT b.clientID, SUM(b.grandTotal) - IFNULL(SUM(d.grandTotal),0)  AS threeMonth
						FROM ms_client a
						JOIN tr_salesorderhead b on a.clientID = b.clientID
						LEFT JOIN tr_clientsettlementdetail c on b.salesNum = c.salesNum
						LEFT JOIN tr_clientsettlementhead d on c.settlementNum = d.settlementNum
						WHERE DATEDIFF(b.dueDate,NOW()) < 90 AND DATEDIFF(b.dueDate,NOW()) > 60 
						GROUP BY b.clientID 
					)e on a.clientID = e.clientID
					LEFT JOIN
					(
						SELECT b.clientID, SUM(b.grandTotal) - IFNULL(SUM(d.grandTotal),0)  AS mustThreeMonth
						FROM ms_client a
						JOIN tr_salesorderhead b on a.clientID = b.clientID
						LEFT JOIN tr_clientsettlementdetail c on b.salesNum = c.salesNum
						LEFT JOIN tr_clientsettlementhead d on c.settlementNum = d.settlementNum
						WHERE DATEDIFF(b.dueDate,NOW()) > 90 
						GROUP BY b.clientID 
					)f on a.clientID = f.clientID
                                        WHERE a.clientID IN (select clientID FROM tr_salesorderhead)
					GROUP BY a.clientID ";
                    $temp = $connection->createCommand($sql);
                    $headResult = $temp->queryAll();
                
                    $i = 1;
                    foreach ($headResult as $row) {
                        ?>
                        <div style="margin: 5px 1%; ">
                            <div style="float:left; width: 5%; text-align: center; font-size: 9px;"><?= $i ?></div>
                            <div style="float:left; width: 20%; font-size: 10px; font-size: 9px;"><?= $row['clientName'] ?>&nbsp;</div>
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
