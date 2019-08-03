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
        <?php
        $connection = Yii::$app->db;
        $sql = "
                SELECT 
                a.Fullname,
                a.birthPlace,
                a.BirthDate,
                a.Address,
                a.City,
                e.key2 as 'MaritalStatus',
                a.dependent as'Dependent',
                c.description as 'Division',
                d.departmentDesc as'DepartmentDesc',
                a.npwpNo,
                f.A01 as 'Salary',
                f.A02 as 'Transportasi',
                f.A03 as 'UangMakan',
                f.A04 as 'UangDriver',
                f.B02 as 'THR',
                f.D01 as 'Loan',
                f.D02 as 'Bonus',
                f.D03 as 'Overtime',
                f.JHTCom as 'JHTCompany',
                f.JHTEmp as 'JHTEmployee',
                f.JKKCom as 'JKKCompany',
                f.JKKEmp as 'JKKEmployee',
                f.JKMCom as 'JKMCompany',
                f.JKMEmp as 'JKMEmployee',
                f.JPKCom as 'JPKCompany',
                f.JPKEmp as 'JPKEmployee',
                f.JPNCom as 'JPNCompany',
                f.JPNEmp as 'JPNEmployee',
                g.pphAmount as'PPH'
                FROM ms_personnelhead a
                LEFT JOIN ms_personneldivision c on c.divisionId = a.divisionID
                LEFT JOIN ms_personneldepartment d on d.departmentCode = a.departmentID
                LEFT JOIN ms_setting e on e.Value1 = a.maritalstatus and e.key1 = 'MaritalStatus'
                LEFT JOIN vr_crosstab f on f.nik = a.id and Period = '" . $periode . "'
                LEFT JOIN tr_payrolltaxmonthlyproc g on a.id = g.nik and g.period = '" . $periode . "'
                where a.id = '" . $fullname . "'";
        $temp = $connection->createCommand($sql);
        $headResult = $temp->queryOne();

        $sumAllowance = 0;
        $sumDeduction = 0;
        $sumAllowance = $headResult['Salary'] +
                $headResult['Transportasi'] +
                $headResult['UangMakan'] +
                $headResult['UangDriver'] +
                $headResult['THR'] +
                $headResult['Bonus'] +
                $headResult['Overtime'] +
                $headResult['JHTCompany'] +
                $headResult['JKKCompany'] +
                $headResult['JKMCompany'] +
                $headResult['JPKCompany'] +
                $headResult['JPNCompany'];

        $sumDeduction = $headResult['JHTCompany'] +
                $headResult['JHTEmployee'] +
                $headResult['JKKCompany'] +
                $headResult['JKMCompany'] +
                $headResult['JPKCompany'] +
                $headResult['JPKEmployee'] +
                $headResult['JPNCompany'] +
                $headResult['JPNEmployee'] +
                $headResult['PPH'];

        $thp = $sumAllowance - $sumDeduction;

//        echo"<pre>";
//        var_dump($headResult);
//        echo"</pre>";
//        yii::$app->end();
        ?>


        <div class="wrap" style="font-size: 12px !important;">
            <div>
                <div style="text-align: center; font-size: 30px; color: Blue;">ESENSI SOLUSI BUANA</div>
              
                <div style="margin-top: 10px;">
                    <div style="float:left; width: 70%;">
                        <div></div>
                    </div>
                    <div style="clear: both;"></div>
                </div>

                <div style="margin-top: 10px; text-align: center; font-size: 16px;"><b>PAYSLIP</b></div>

                <div style="margin-top: 10px; padding: 10px 10px 10px 10px;">
                    <div style="float:left; width: 60%;">
                        <div>
                            <div style="width: 115px; float: left;">PAYROLL PERIOD</div>
                            <div style="display:block; float: left;">: <?= $periode ?> </div>
                            <div style="clear: both;"></div>
                        </div>

                        <div>
                            <div style="width: 115px; float: left;">FULL NAME</div>
                            <div style="display:block; float: left;">: <?= $headResult['Fullname'] ?> </div>
                            <div style="clear: both;"></div>
                        </div>

                        <div>
                            <div style="width: 115px; float: left;">DIVISION</div>
                            <div style="display:block; float: left;">: <?= $headResult['Division'] ?>  </div>
                            <div style="clear: both;"></div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                </div>
                <div style="border-bottom: #000 solid 1px; padding-bottom: 10px;">
                </div>

                <br>
                <div style="float:left; width: 50%;"> <b> Allowance </b>
                    <br>
                    <br>
                    <div>
                        <div style="width: 150px; float: left;">Salary</div>
                        <div style="display:block; float: left;">: Rp. <?= number_format($headResult['Salary'], 2, ",", ".") ?></div>
                        <div style="width: 150px; float: left;">Transport</div>
                        <div style="display:block; float: left;">: Rp. <?= number_format($headResult['Transportasi'], 2, ",", ".") ?></div>
                        <div style="width: 150px; float: left;">Uang Makan</div>
                        <div style="display:block; float: left;">: Rp. <?= number_format($headResult['UangMakan'], 2, ",", ".") ?></div>
                        <div style="width: 150px; float: left;">Uang Driver</div>
                        <div style="display:block; float: left;">: Rp. <?= number_format($headResult['UangDriver'], 2, ",", ".") ?></div>
                        <div style="width: 150px; float: left;">THR</div>
                        <div style="display:block; float: left;">: Rp. <?= number_format($headResult['THR'], 2, ",", ".") ?></div>
                        <div style="width: 150px; float: left;">Bonus</div>
                        <div style="display:block; float: left;">: Rp. <?= number_format($headResult['Bonus'], 2, ",", ".") ?></div>
                        <div style="width: 150px; float: left;">Overtime</div>
                        <div style="display:block; float: left;">: Rp. <?= number_format($headResult['Overtime'], 2, ",", ".") ?></div>
                        <div style="width: 150px; float: left;">JHTCompany</div>
                        <div style="display:block; float: left;">: Rp. <?= number_format($headResult['JHTCompany'], 2, ",", ".") ?></div>
                        <div style="width: 150px; float: left;">JKKCompany</div>
                        <div style="display:block; float: left;">: Rp. <?= number_format($headResult['JKKCompany'], 2, ",", ".") ?></div>
                        <div style="width: 150px; float: left;">JKMCompany</div>
                        <div style="display:block; float: left;">: Rp. <?= number_format($headResult['JKMCompany'], 2, ",", ".") ?></div>
                        <div style="width: 150px; float: left;">JPKCompany</div>
                        <div style="display:block; float: left;">: Rp. <?= number_format($headResult['JPKCompany'], 2, ",", ".") ?></div>
                        <div style="width: 150px; float: left;">JPNCompany</div>
                        <div style="display:block; float: left;">: Rp. <?= number_format($headResult['JPNCompany'], 2, ",", ".") ?></div>
                        <div style="width: 150px; float: left;"><b>Total Allowance</b></div>
                        <div style="display:block; float: left;"><b>: Rp. <?= number_format($sumAllowance, 2, ",", ".") ?> </b></div>
                    </div>                            
                    <div style="clear: both;"></div>

                </div>
                <div style="float:left; width: 50%;"> <b> Deduction </b>
                    <br>
                    <br>
                    <div style="width: 150px; float: left;">Loan</div>
                    <div style="display:block; float: left;">: Rp. <?= number_format($headResult['Loan'], 2, ",", ".") ?></div>                       
                    <div style="width: 150px; float: left;">JHTCompany</div>
                    <div style="display:block; float: left;">: Rp. <?= number_format($headResult['JHTCompany'], 2, ",", ".") ?></div>                        
                    <div style="width: 150px; float: left;">JHTEmployee</div>
                    <div style="display:block; float: left;">: Rp. <?= number_format($headResult['JHTEmployee'], 2, ",", ".") ?></div>
                    <div style="width: 150px; float: left;">JKKCompany</div>
                    <div style="display:block; float: left;">: Rp. <?= number_format($headResult['JKKCompany'], 2, ",", ".") ?></div>
                    <div style="width: 150px; float: left;">JKMCompany</div>
                    <div style="display:block; float: left;">: Rp. <?= number_format($headResult['JKMCompany'], 2, ",", ".") ?></div>
                    <div style="width: 150px; float: left;">JPKCompany</div>
                    <div style="display:block; float: left;">: Rp. <?= number_format($headResult['JPKCompany'], 2, ",", ".") ?></div>
                    <div style="width: 150px; float: left;">JPKEmployee</div>
                    <div style="display:block; float: left;">: Rp. <?= number_format($headResult['JPKEmployee'], 2, ",", ".") ?></div>
                    <div style="width: 150px; float: left;">JPNCompany</div>
                    <div style="display:block; float: left;">: Rp. <?= number_format($headResult['JPNCompany'], 2, ",", ".") ?></div>
                    <div style="width: 150px; float: left;">JPNEmployee</div>
                    <div style="display:block; float: left;">: Rp. <?= number_format($headResult['JPNEmployee'], 2, ",", ".") ?></div>
                    <div style="width: 150px; float: left;">PPH 21</div>
                    <div style="display:block; float: left;">: Rp. <?= number_format($headResult['PPH'], 2, ",", ".") ?></div>

                    <div style="width: 150px; float: left;"><b>Total Deduction</b></div>
                    <div style="display:block; float: left;"><b>: Rp. <?= number_format($sumDeduction, 2, ",", ".") ?> </b></div>

                </div>
                <div style="clear: both;"></div>
                <br>
                <div style="border-bottom: #000 solid 1px; padding-bottom: 10px;"></div>

                <div style="float:right; width: 50%; padding: 10px 10px 10px 10px;">
                    <div style="float:left; width: 30%;"><b>Take Home Pay</b></div>
                    <div style="float:left; width: 70%; text-align: right;">: Rp. <?= number_format($thp, 2, ",", ".") ?></div>
                </div>

                <div style="float:left; width: 40%; padding: 10px 10px 10px 10px;" >
                    <div style="display:block; float: left; font-size: 7px;">* This is a computer generated printout and no signature is required</div>
                </div> 

                <div style="clear: both;"></div>

                <?php $this->endBody() ?>
            </div>
        </div>
    </body>
</html>
<?php $this->endPage() ?>