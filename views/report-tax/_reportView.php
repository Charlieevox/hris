<?php $this->beginPage() ?>
<?php $this->title = 'Edit Product - '; ?>

<!DOCTYPE html>
<html>
    <head>
        <?php $this->head() ?>
    </head>
    <body>
	
        <?php $this->beginBody() ?>
        
        <?php
                    $connection = Yii::$app->db;
                    $sql1 = "
                            SELECT fullName
                            FROM 
                            ms_personnelhead where id = '" . $id . "'";
                    $temp1 = $connection->createCommand($sql1);
                    $head = $temp1->queryOne();
                ?>
        
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
                            <div style="display:block; float: left;">: <?= $periode ?> </div>
                            <div style="clear: both;"></div>
                        </div>
                        <div>
                            <div style="width: 115px; float: left;">NAME</div>
                            <div style="display:block; float: left;">: <?= $head['fullName'] ?> </div>
                            <div style="clear: both;"></div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                </div>

                <?php
                    $connection = Yii::$app->db;
                    $sql = "
                            SELECT a.period,
                            b.fullName,
                            a.T01,
                            a.T02,a.
                            T03,
                            a.T04,
                            a.T05,
                            a.T06,
                            a.T07,
                            a.biayajabatan,
                            a.T10,
                            a.ptkp,
                            a.pphcalc,
                            a.pphamount 
                            FROM 
                            tr_payrolltaxmonthlyproc a
                            LEFT JOIN ms_personnelhead b on a.nik = b.id
                            where a.nik = '" . $id . "' and left(a.period,4) = '" . $periode . "'";
                    $temp = $connection->createCommand($sql);
                    $headResult = $temp->queryAll();
//                    echo "<pre>";
//                    var_dump($sql);
//                    echo "</pre>";
//                    Yii::$app->end();
                ?>
                
                  <div style="padding: 10px 10px 10px 10px;">
                    <div style="margin-top: 10px;"><b><i>Tax Detail</i></b></div>
                    <div style="border-bottom: #000 solid 1px; margin: 10px 1% 0px 1%; padding-bottom: 10px;">
                        <div style="float:left; width: 6%; text-align: center; font-size: 10px;">Period</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;">T01</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;">T02</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;">T03</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;">T04</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;">T05</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;">T06</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;">T07</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;">Biaya Jabatan</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;">T10</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;">PTKP</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;">PPH Calc</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;">PPH Amount</div>
                        <div style="clear: both;"></div>
                    </div>
                    
                    <?php
                    $i = 1;
                    foreach ($headResult as $row) {
                        ?>
                        <div style="margin: 5px 1%; ">
                            <div style="float:left; width: 6%; text-align: center; font-size: 10px;"><?= $row['period'] ?>&nbsp;</div>
                            <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($row['T01'], 2, ",", ".") ?>&nbsp;</div>
                            <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($row['T02'], 2, ",", ".") ?>&nbsp;</div>
                            <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($row['T03'], 2, ",", ".") ?>&nbsp;</div>
                            <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($row['T04'], 2, ",", ".") ?>&nbsp;</div>
                            <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($row['T05'], 2, ",", ".") ?>&nbsp;</div>
                            <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($row['T06'], 2, ",", ".") ?>&nbsp;</div>
                            <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($row['T07'], 2, ",", ".") ?>&nbsp;</div>
                            <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($row['biayajabatan'], 2, ",", ".") ?>&nbsp;</div>
                            <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($row['T10'], 2, ",", ".") ?>&nbsp;</div>
                            <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($row['ptkp'], 2, ",", ".") ?>&nbsp;</div>
                            <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($row['pphcalc'], 2, ",", ".") ?>&nbsp;</div>
                            <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($row['pphamount'], 2, ",", ".") ?>&nbsp;</div>
                            <div style="clear: both;"></div>
                        </div>
                        <?php
                        $i = $i + 1;
                    }
                    ?>
                    
                    
                    <?php
                    $T01 = 0;
					$T02 = 0;
					$T03 = 0;
					$T04 = 0;
					$T05 = 0;
					$T06 = 0;
					$T07 = 0;
					$T10 = 0;
					$pphCalc = 0;
					$Pphamount = 0;
					$biayaJabatan = 0;
					$ptkp = 0;
                    foreach ($headResult as $row) {
                        $T01 = $T01 + $row['T01'];
						$T02 = $T02 + $row['T02'];
						$T03 = $T03 + $row['T03'];
						$T04 = $T04 + $row['T04'];
						$T05 = $T05 + $row['T05'];
						$T06 = $T06 + $row['T06'];
						$T07 = $T07 + $row['T07']; 
						$T10 = $T10 + $row['T10']; 
						$biayaJabatan =  $row['biayajabatan'];
						$pphCalc =  $row['pphcalc'];
						$Pphamount = $Pphamount + $row['pphamount'];
						$ptkp = $row['ptkp'];
						
                    }
                    ?>
                    
                    <div style="border-top: #000 solid 1px; margin: 10px 1% 0px 1%; padding-bottom: 10px;"></div>
                    <div style="margin: 5px 1%; ">
                        <div style="float:left; width: 6%; text-align: center; font-size: 10px;">TOTAL&nbsp;</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($T01, 2, ",", ".") ?>&nbsp;</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($T02, 2, ",", ".") ?>&nbsp;</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($T03, 2, ",", ".") ?>&nbsp;</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($T04, 2, ",", ".") ?>&nbsp;</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($T05, 2, ",", ".") ?>&nbsp;</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($T06, 2, ",", ".") ?>&nbsp;</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($T07, 2, ",", ".") ?>&nbsp;</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($biayaJabatan, 2, ",", ".") ?>&nbsp;</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($T10, 2, ",", ".") ?>&nbsp;</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($ptkp, 2, ",", ".") ?>&nbsp;</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($pphCalc, 2, ",", ".") ?>&nbsp;</div>
                        <div style="float:left; width: 7.7%; text-align: right; font-size: 10px;"><?= number_format($Pphamount, 2, ",", ".") ?>&nbsp;</div>
                        <div style="clear: both;"></div>
                    </div>                    
                    
                
                

                <?php $this->endBody() ?>
            </div>
        </div>
    </body>

</html>
<?php $this->endPage() ?>