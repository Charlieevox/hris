<?php

namespace app\models;

use app\components\AppHelper;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;

/**
 * This is the model class for table "ms_customer".
 * 
 * @property string $dateFrom
 * @property string $dateTo
 */
class Report extends \yii\db\ActiveRecord
{
    
    public $clientID;
    public $clientName;
    
	public $supplierID;
    public $supplierName;
	
    public $dateFrom;
    public $dateTo;
    
    public $yearReport;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.ms_client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['clientName', 'clientID','supplierID','supplierName'], 'required'],
            [['clientID','supplierID'], 'integer'],
            [['dateFrom', 'dateTo'], 'date', 'format' => 'php:d-m-Y'],
            [['dateFrom', 'dateTo'], 'required'],
            [['yearReport'], 'string', 'max' => 4],
            [['yearReport'], 'required'],
        ];
    }

    public function scenarios()
    {
        $scenario = parent::scenarios();
		$scenario['cash-in'] = [
            'dateFrom',
            'dateTo'
        ];
        $scenario['document-tracking'] = [
            'dateFrom',
            'dateTo'
        ];
		
	$scenario['sales-order'] = [
            'dateFrom',
            'dateTo'
        ];
		
	$scenario['purchase-order'] = [
            'dateFrom',
            'dateTo'
        ];
		
	$scenario['cash-out'] = [
            'dateFrom',
            'dateTo'
        ];
        $scenario['actual-time-sheet'] = [
            'dateFrom',
            'dateTo'
        ];
        
        $scenario['minutes-of-meeting'] = [
            'dateFrom',
            'dateTo'
        ];
        
        $scenario['time-sheet-schedule'] = [
            'dateFrom',
            'dateTo'
        ];
          
        $scenario['task-progress'] = [
            'dateFrom',
            'dateTo'
        ];
        
        $scenario['supplier-payment'] = [
            'dateFrom',
            'dateTo'
        ];
         
        $scenario['client-settlement'] = [
            'dateFrom',
            'dateTo'
        ];
        
        return $scenario;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dateFrom' => 'From',
            'dateTo' => 'To',
            'clientID' => 'Client',
            'clientName' => 'Client',
            'yearReport' => 'Tahun',
			'supplierID' => 'Supplier',
            'supplierName' => 'Supplier',
        ];
    }
}
