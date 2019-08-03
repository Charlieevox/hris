<?php

namespace app\models;

use yii\base\Model;
use yii\data\SqlDataProvider;

class _Index extends Model
{
    public function search()
    {
        $sql =  'SELECT A.receiptID, A.receiptDate, concat(E.itemBrand, \' \', D.itemType, \' (\', C.vehiclePoliceNo, \')\') AS vehicleName, 
                (
                    SELECT group_concat(B.vehicleDocAcc separator \', \') FROM (SELECT vehicleDocAccID FROM ms_vehicledoc_acc WHERE isMandatory = 1) A 
                    LEFT JOIN ms_vehicledoc_acc B ON A.vehicleDocAccID = B.vehicleDocAccID
                    WHERE A.vehicleDocAccID NOT IN (SELECT vehicleDocAccID FROM tr_receipt_doc_acc WHERE receiptID = A.receiptID)
                ) AS documentString
                FROM ms_receipt A 
                LEFT JOIN ms_vehicle C ON A.vehicleID = C.vehicleID
                LEFT JOIN ms_itemtype D ON C.vehicleTypeID = D.itemTypeID
                LEFT JOIN ms_itembrand E ON D.itemBrandID = E.itemBrandID
                WHERE isReceive = 1 
                HAVING documentString IS NOT NULL  
                ORDER BY A.receiptDate DESC';

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => count($sql),
        ]);
        
        $dataProvider->totalCount = $dataProvider->count;
        
        return $dataProvider;
    }
}
