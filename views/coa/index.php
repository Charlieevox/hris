<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widget\Pjax;
use app\models\MsCoa;
use yii\bootstrap\Modal;

$this->title = 'Master COA';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="box box-default">
    <div class="box-body">
        <div class="coa-index table-responsive">
            <table class="table">
                <tr>
                    <th colspan="2">Chart of Account Management</th>
                </tr>
                <?php 
                    $connection = Yii::$app->db;
                    $sql = "SELECT a.coaNo, a.coaLevel, a.description
                            FROM ms_coa a
                            WHERE a.coaLevel = 1
                            ORDER BY a.coaNo ";
                    $model = $connection->createCommand($sql);
                    $result = $model->queryAll();
                    $counter1 = 0;
                    foreach ($result as $loopResult) {
                            echo 
                            "<tr class='level''>
                                <td colspan='2' style='text-decoration: underline; cursor: pointer;'>". $loopResult['description'] ."</td>
                            </tr>
                            <tr class='leveld'>
                                <td colspan='2'>
                                    <table>";
                                        $counter1 = $counter1+1;
                                        $sql = "SELECT a.coaNo, a.coaLevel, a.description
                                                FROM ms_coa a
                                                WHERE a.coaLevel = 2 AND a.coaNo LIKE '". substr($loopResult['coaNo'],0,1) ."%'
                                                ORDER BY a.coaNo ";
                            
                                                $model = $connection->createCommand($sql);
                                                $result2 = $model->queryAll();

                                                $counter2 = 0;
                                                foreach ($result2 as $loopResult2) {
                                                    echo 
                                                            "<tr class='level'>
                                                                <td style='padding-left:30px' colspan='2'></td>
                                                                <td style='text-decoration: underline; cursor: pointer;'>". $loopResult2['description'] ."</td>
                                                            </tr>
                                                            <tr class='leveld'>
                                                                <td></td>
                                                                <td></td>
                                                                <td>
                                                                    <table>";
                                                                        // Hitung No MAX Level 3
                                                                       $sql =  "SELECT max(coaNo) AS no, max(ordinal) AS ordinal, a.coaNo, a.coaLevel, a.description
                                                                                FROM ms_coa a
                                                                                WHERE a.coaLevel = 3 AND a.coaNo LIKE '". substr($loopResult2['coaNo'],0,4) ."%'
                                                                                ORDER BY a.coaNo";

                                                                                $model = $connection->createCommand($sql);
                                                                                $resultMaxLevel3 = $model->queryAll(); 
                                                                                                                                                               
                                                                                foreach ($resultMaxLevel3 as $loopResultMaxLevel3) {
                                                                                    
                                                                                    $headMax = substr($loopResult2['coaNo'],0,4);
                                                                                    $noMax = substr($loopResultMaxLevel3['no'],3,-3);
                                                                                    $hind = substr($loopResultMaxLevel3['no'],-3);
                                                                                    $ordinal = $loopResultMaxLevel3['ordinal'];

                                                                                    $noMax = $noMax +1;
                                                                                    $hidden = '';
                                                                                    if($noMax > 9 ){
                                                                                        $hidden = 'hidden';
                                                                                    }
                                                                                    $ordinal = $ordinal + 1;
                                                                                    $no = $headMax."".$noMax."".$hind;
                                                                                echo " <tr>
                                                                                            <td></td>
                                                                                            <td>".Html::button('<span class="glyphicon glyphicon-plus '.$hidden.'" aria-hidden="true"></span>', ['value'=>Url::to(['save', 'id' => $no, 'ordinal' => $ordinal ]), 'class'=>'btn btn-primary modalButton '.$hidden, 'title' => 'Add COA Level 3' ])."</td>
                                                                                        </tr>";
                                                                                }
                                                                        
                                                                        $sql = "SELECT a.coaNo, a.coaLevel, a.description
                                                                        FROM ms_coa a
                                                                        WHERE a.coaLevel = 3 AND a.coaNo LIKE '". substr($loopResult2['coaNo'],0,3) ."%'
                                                                        ORDER BY a.coaNo ";

                                                                        $model = $connection->createCommand($sql);
                                                                        $result3 = $model->queryAll();
                                                                        foreach ($result3 as $loopResult3) {
                                                                            echo 
                                                                                    "<tr class='level'>
                                                                                        <td style='padding-left:30px'></td>
                                                                                        <td style='text-decoration: underline; cursor: pointer;'>". $loopResult3['description'] ."</td>
                                                                                    </tr>
                                                                                    <tr class='leveld'>
                                                                                        <td></td>
                                                                                        <td>
                                                                                            <table class='' >
                                                                                                <tbody>";

                                                                                                    $sql = "SELECT max(coaNo) AS no, max(ordinal) AS ordinal, a.coaNo, a.coaLevel, a.description
                                                                                                    FROM ms_coa a
                                                                                                    WHERE a.coaLevel = 4 AND a.coaNo LIKE '". substr($loopResult3['coaNo'],0,5) ."%'
                                                                                                    ORDER BY a.coaNo";

                                                                                                    $model = $connection->createCommand($sql);
                                                                                                    $resultMax = $model->queryAll(); 

                                                                                                    foreach ($resultMax as $loopResultMax) {
                                                                                                        //$max = $loopResultMax['no'] ;
                                                                                                        //$no = $max +1;
                                                                                                        
                                                                                                        $headMax = substr($loopResult3['coaNo'],0,5);
                                                                                                        $noMax = substr($loopResultMax['no'],-2);
                                                                                                        $ordinal = $loopResultMax['ordinal'];

                                                                                                        $ordinal = $ordinal + 1;
                                                                                                        $no = $noMax + 1;
                                                                                                        $no = str_pad($no,2,"0",STR_PAD_LEFT);
                                                                                                        $no = $headMax." ".$no;
                                                                                                        
                                                                                                    echo 
                                                                                                        "   
                                                                                                            <tr>
                                                                                                                <td style='padding-left:30px' >".Html::button('<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>', ['value'=>Url::to(['create', 'id' => $no, 'ordinal' => $ordinal ]), 'class'=>'btn btn-primary modalButton', 'title' => 'Add COA Level 4' ])."</td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td colspan='2'>
                                                                                                                    <table class='tblv4 table-hover'>
                                                                                                                        <tbody> ";
                                                    
                                                                                                                            $sql = "SELECT a.coaNo, a.coaLevel, a.description, b.counter
                                                                                                                            FROM ms_coa a
                                                                                                                            LEFT JOIN
                                                                                                                            (
                                                                                                                            SELECT coaNo AS coaNo, COUNT(*) AS counter FROM ms_category GROUP BY coaNo
                                                                                                                            UNION
                                                                                                                            SELECT assetCOA AS coaNo, COUNT(*) AS counter FROM ms_assetcategory GROUP BY assetCOA
                                                                                                                            UNION
                                                                                                                            SELECT depCOA AS coaNo, COUNT(*) AS counter FROM ms_assetcategory GROUP BY depCOA
                                                                                                                            UNION
                                                                                                                            SELECT expCOA AS coaNo, COUNT(*) AS counter FROM ms_assetcategory GROUP BY expCOA
                                                                                                                            )b on a.coaNo = b.coaNO                                                        
                                                                                                                            WHERE a.coaLevel = 4 AND a.coaNo LIKE '". substr($loopResult3['coaNo'],0,5) ."%'
                                                                                                                            AND a.flagActive = 1
                                                                                                                            ORDER BY a.ordinal";

                                                                                                                            $model = $connection->createCommand($sql);
                                                                                                                            $result4 = $model->queryAll();
                                                                                                                            foreach ($result4 as $loopResult4) {
                                                                                                                                $confirm = "";
                                                                                                                                if ($loopResult4['counter'] <> NULL) {
                                                                                                                                      $confirm = 'data cannot be deleted due to its connection to other application or transaction?';
                                                                                                                                }else{
                                                                                                                                      $confirm = 'Are you sure you want to delete this Account ?';
                                                                                                                                }
                                                                                                                                echo 
                                                                                                                                        "<tr class='level4'>
                                                                                                                                                <td style='padding-left:30px; padding-right:20px;' value= '". $loopResult4['coaNo'] ."'>". $loopResult4['description'] ."</td>
                                                                                                                                                <td>".Html::button('<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>', ['value' => Url::to(['update', 'id' => $loopResult4['coaNo']]), 'class'=>'modalButton', 'style' => 'background-color:white; border:none;' ])."
                                                                                                                                                    ".Html::a('<span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>', ['delete', 'id' => "".$loopResult4['coaNo'].""], ['data' => [
                                                                                                                                                    'confirm' => $confirm,
                                                                                                                                                    'method' => 'post',
                                                                                                                                                ]])."
                                                                                                                                                </td>
                                                                                                                                        </tr>";
                                                                                                                                }

                                                                                                                    echo "              
                                                                                                                        </tbody>
                                                                                                                    </table>
                                                                                                                </td>
                                                                                                            </tr>";  
                                                                                                    }
                                                                                            echo 
                                                                                                "</tbody>
                                                                                            </table>	
                                                                                        </td>
                                                                                 </tr>";
                                                                        }
                                                                echo 
                                                                    "</table>	
                                                                </td>
                                                            </tr>";
                                                }
                                echo 
                                    "</table>	
                                </td>
                            </tr>";
                    }
                ?>
            </table>
        </div>
    </div>
</div>

<?php
    Modal::begin([
        'header' => '',
        'id' => 'modal',
        'size' => 'modal-lg',
    ]);

    echo "<div id='modalContent'><div>";
    Modal::end()
?>


<?php
$coaAjaxURL = Yii::$app->request->baseUrl. '/coa/order';

$js = <<< SCRIPT
        
$(document).ready(function () {
        
        $('.tblv4').sortable({
	items: "tr",
        revert: true,
        placeholder: "ui-state-highlight",
        //start: function(event, ui) { $('#loading-div').show(); },
        //stop: function(event, ui) { $('#loading-div').hide(); },
	beforeStop: function(event, ui) {
        
        newIndex = $(ui.helper).index('.level4');
			var counter = 0;
			$(ui.helper).parent().find('tr').each(function() {
				rowVal = $(this).find('td').attr('value');
				if (rowVal != undefined)
				{
					counter = counter + 1;
                                        //$('#loading-div').show(0).delay(1000).hide(0);
					setCOAOrder(rowVal,counter); 
				}
            });
        }
	}).disableSelection();
        
        
        $(".modalButton").click(function(){
        $("#modal").modal('show')
             .find("#modalContent")
             .load($(this).attr('value'));
        });
        
	$('.leveld').hide();
	
	$('.level').click(function() {
		console.log(1);
		if ($(this).next().is(":visible"))
		{
                    $(this).next().hide();
		}
		else
		{
                    $(this).next().show();
		}
	}); 
	
        function setCOAOrder(coaNo, ordinal){
        $.ajax({
            url: '$coaAjaxURL',
            async: false,
            type: 'POST',
            data: { coaNo: coaNo, ordinal: ordinal },
                   success: function(data) {
                   console.log(data);
		}
         });
        }
        
        $(document).ajaxStart(function(){
            $('#loading-div').show();
        }).ajaxStop(function(){
            $('#loading-div').hide();
        });
});
SCRIPT;
$this->registerJs($js);
?>
