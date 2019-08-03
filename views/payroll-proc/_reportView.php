<?php

use app\models\TrPayrollProc;

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
                        <div></div>
                    </div>
                    <div style="clear: both;"></div>
                </div>

                <div style="margin-top: 10px; text-align: center; font-size: 16px;"><b>PAYROLL REPORT</b></div>

                <div style="margin-top: 10px; padding: 10px 10px 10px 10px;">
                    <div style="float:left; width: 60%;">
                        <div>
                            <div style="width: 115px; float: left;">PAYROLL PERIOD</div>
                            <div style="display:block; float: left;">: <?= $model->period ?> </div>
                            <div style="clear: both;"></div>
                        </div>
                        <div>
                            <div style="width: 115px; float: left;">DATE</div>
                            <div style="display:block; float: left;">: <?= date("d-m-Y") ?> </div>
                            <div style="clear: both;"></div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                </div>

                <div style="padding: 10px 10px 10px 10px;">
                    <div style="margin-top: 10px;"><b><i>Payroll Detail</i></b></div>
                    <div style="border-bottom: #000 solid 1px; margin: 10px 1% 0px 1%; padding-bottom: 10px;">
                        <div style="float:left; width: 5%; text-align: left; font-size: 10px;">id</div>
                        <div style="float:left; width: 15%; text-align: left; font-size: 10px;">Fullname</div>
                        <div style="float:left; width: 12%; text-align: left; font-size: 10px;">Allowance</div>
                        <div style="float:left; width: 12%; text-align: left; font-size: 10px;">Deduction</div>
                        <div style="float:left; width: 12%; text-align: left; font-size: 10px;">Jamsostek Allowance</div>
                        <div style="float:left; width: 12%; text-align: left; font-size: 10px;">Jamsostek Deduction</div>
                        <div style="float:left; width: 12%; text-align: left; font-size: 10px;">PPH</div>
                        <div style="float:left; width: 12%; text-align: left; font-size: 10px;">THP</div>
                        <div style="clear: both;"></div>
                    </div>

                    <?php
                    $connection = Yii::$app->db;
                    $sql = "
                        SELECT 
                        a.id,
                        a.Fullname,
                        (f.a01+f.A02+f.A03+f.A04+f.B02+f.D02+f.D03) as 'Allowance',
                        (f.D01) as 'Deduction',
                        (f.JKKCom + f.JKMCom + f.JHTCom            + f.JPKCom            + f.JPNCom) as 'JamsostekAllowance',
                        (f.JKKCom + f.JKMCom + f.JHTCom + f.JHTEmp + f.JPKCom + f.JPKEmp + f.JPNCom + f.JPNEmp) as 'JamsostekDeduction',
                        g.pphAmount as'PPH',
                        ((f.a01+f.A02+f.A03+f.A04+f.B02+f.D02+f.D03)
                        +
                        (f.JKKCom +f.JKMCom + f.JHTCom + f.JPKCom + f.JPNCom))
                        -
                        ((f.D01)
                        +
                        (f.JKKCom + f.JKMCom + f.JHTCom + f.JHTEmp + f.JPKCom + f.JPKEmp + f.JPNCom + f.JPNEmp) + g.pphAmount ) as 'THP'

                        FROM ms_personnelhead a
                        LEFT JOIN ms_personnelposition b on b.id = a.position
                        LEFT JOIN ms_personneldivision c on c.divisionId = a.divisionID
                        LEFT JOIN ms_personneldepartment d on d.departmentCode = a.departmentID
                        LEFT JOIN ms_setting e on e.Value1 = a.maritalstatus and e.key1 = 'MaritalStatus'
                        JOIN vr_crosstab f on f.nik = a.id and Period = '" . $model->period . "'
                        JOIN tr_payrolltaxmonthlyproc g on a.id = g.nik and g.period = '" . $model->period . "'";
                    $temp = $connection->createCommand($sql);
                    $headResult = $temp->queryAll();

                    $i = 1;
                    foreach ($headResult as $row) {
                        ?>
                        <div style="margin: 5px 1%; ">
                            <div style="float:left; width: 5%; font-size: 10px; font-size: 9px;"><?= $row['id'] ?>&nbsp;</div>
                            <div style="float:left; width: 15%; font-size: 10px; font-size: 9px;"><?= $row['Fullname'] ?>&nbsp;</div>
                            <div style="float:left; width: 12%; font-size: 10px; font-size: 9px;"><?= number_format($row['Allowance'], 2, ",", ".") ?>&nbsp;</div>
                            <div style="float:left; width: 12%; font-size: 10px; font-size: 9px;"><?= number_format($row['Deduction'], 2, ",", ".") ?>&nbsp;</div>
                            <div style="float:left; width: 12%; font-size: 10px; font-size: 9px;"><?= number_format($row['JamsostekAllowance'], 2, ",", ".") ?>&nbsp;</div>
                            <div style="float:left; width: 12%; font-size: 10px; font-size: 9px;"><?= number_format($row['JamsostekDeduction'], 2, ",", ".") ?>&nbsp;</div>
                            <div style="float:left; width: 12%; font-size: 10px; font-size: 9px;"><?= number_format($row['PPH'], 2, ",", ".") ?>&nbsp;</div>
                            <div style="float:left; width: 12%; font-size: 10px; font-size: 9px;"><?= number_format($row['THP'], 2, ",", ".") ?>&nbsp;</div>
                            <div style="clear: both;"></div>
                        </div>
                        <?php
                        $i = $i + 1;
                    }
                    ?>

                    <?php
                    $Allowance = 0;
                    $Deduction = 0;
                    $JamsostekAllowance = 0;
                    $JamsostekDeduction = 0;
                    $PPH = 0;
                    $THP = 0;
                    foreach ($headResult as $row) {
                        $Allowance = $Allowance + $row['Allowance'];
                        $Deduction = $Deduction + $row['Deduction'];
                        $JamsostekAllowance = $JamsostekAllowance + $row['JamsostekAllowance'];
                        $JamsostekDeduction = $JamsostekDeduction + $row['JamsostekDeduction'];
                        $PPH = $PPH + $row['PPH'];
                        $THP = $THP + $row['THP'];
                    }
                    ?>
                    <div style="border-top: #000 solid 1px; margin: 10px 1% 0px 1%; padding-bottom: 10px;">
                        <div style="margin: 5px 1%; ">
                            <div style="float:left; width: 5%; font-size: 10px; font-size: 9px;"><b>Total &nbsp;</b></div>
                            <div style="float:left; width: 15%; font-size: 10px; font-size: 9px;">&nbsp;</div>
                            <div style="float:left; width: 12%; font-size: 10px; font-size: 9px;"><?= number_format($Allowance, 2, ",", ".") ?>&nbsp;</div>
                            <div style="float:left; width: 12%; font-size: 10px; font-size: 9px;"><?= number_format($Deduction, 2, ",", ".") ?>&nbsp;</div>
                            <div style="float:left; width: 12%; font-size: 10px; font-size: 9px;"><?= number_format($JamsostekAllowance, 2, ",", ".") ?>&nbsp;</div>
                            <div style="float:left; width: 12%; font-size: 10px; font-size: 9px;"><?= number_format($JamsostekDeduction, 2, ",", ".") ?>&nbsp;</div>
                            <div style="float:left; width: 12%; font-size: 10px; font-size: 9px;"><?= number_format($PPH, 2, ",", ".") ?>&nbsp;</div>
                            <div style="float:left; width: 12%; font-size: 10px; font-size: 9px;"><?= number_format($THP, 2, ",", ".") ?>&nbsp;</div>
                            <div style="clear: both;"></div>
                        </div>                    
                    </div>
                </div>

                <?php $this->endBody() ?>
            </div>
        </div>
    </body>

</html>
<?php $this->endPage() ?>