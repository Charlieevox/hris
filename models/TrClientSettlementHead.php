<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "tr_clientsettlementhead".
 *
 * @property string $settlementNum
 * @property string $settlementDate
 * @property integer $clientID
 * @property string $currencyID
 * @property string $rate
 * @property string $grandTotal
 * @property integer $taxID
 * @property string $additionalInfo
 * @property string $authorizationNotes
 * @property string $settlementName
 * @property string $settlementApproval
 * @property integer $status
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 *
 * @property TrClientsettlementdetail[] $trClientsettlementdetails
 * @property MsClient $client
 * @property LkCurrency $currency
 * @property MsTax $tax
 */
class TrClientSettlementHead extends \yii\db\ActiveRecord
{
    public $joinClientSettlementDetail;
    public $flagClient;
    public $flagClientName;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_clientsettlementhead';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['settlementNum', 'settlementDate', 'clientID', 'currencyID', 'rate', 'locationID', 'grandTotal', 'coaNo', 'taxID', 'settlementName', 'status', 'createdBy', 'createdDate'], 'required'],
            [['settlementDate', 'createdDate', 'editedDate'], 'safe'],
            [['clientID', 'taxID', 'status', 'locationID'], 'integer'],
            [['rate'], 'number'],
            [['settlementNum', 'settlementName', 'settlementApproval', 'createdBy', 'editedBy', 'coaNo'], 'string', 'max' => 50],
            [['currencyID'], 'string', 'max' => 10],
            [['additionalInfo', 'authorizationNotes'], 'string', 'max' => 200],
            [['settlementNum','settlementDate','clientID','currencyID','additionalInfo','grandTotal','coano','locationID'], 'safe', 'on'=>'search'],
            [['joinClientSettlementDetail'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'settlementNum' => 'Settlement Number',
            'settlementDate' => 'Settlement Date',
            'clientID' => 'Client Name',
            'currencyID' => 'Currency ID',
            'rate' => 'Rate',
            'grandTotal' => 'Grand Total',
			'coaNo' => 'Cash Account',
            'taxID' => 'Tax Type',
            'additionalInfo' => 'Additional Information',
            'authorizationNotes' => 'Authorization Notes',
            'settlementName' => 'Settlement Name',
            'settlementApproval' => 'Settlement Approval',
            'status' => 'Status',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'locationID' => 'Location Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientsettlementdetails()
    {
        return $this->hasMany(TrClientSettlementDetail::className(), ['settlementNum' => 'settlementNum']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(MsClient::className(), ['clientID' => 'clientID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(LkCurrency::className(), ['currencyID' => 'currencyID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTax()
    {
        return $this->hasOne(MsTax::className(), ['taxID' => 'taxID']);
    }
	
	 public function getCoaNos()
    {
        return $this->hasOne(MsCoa::className(), ['coaNo' => 'coaNo']);
    }
    
        public function getStatus1()
	{
        return $this->hasOne(MsStatus::className(), ['statusID' => 'status'])->onCondition(['statusKey' => 'Invoice Settlement']);
    }
    
    public function search()
    {
    	$query = self::find()
    	->joinWith('currency')
    	->joinWith('tax')
    	->joinWith('client')
        ->joinWith('coaNos')
        ->joinWith('status1')
    	->andFilterWhere(['like', 'tr_clientsettlementhead.settlementNum', $this->settlementNum])
    	->andFilterWhere(['=', "DATE_FORMAT(tr_clientsettlementhead.settlementDate, '%d-%m-%Y')", $this->settlementDate])
    	->andFilterWhere(['=', 'tr_clientsettlementhead.grandTotal', $this->grandTotal])
    	->andFilterWhere(['=', 'tr_clientsettlementhead.clientID', $this->clientID])
    	->andFilterWhere(['=', 'tr_clientsettlementhead.currencyID', $this->currencyID])
        ->andFilterWhere(['=', 'tr_clientsettlementhead.locationID', $this->locationID])
	->andFilterWhere(['=', 'tr_clientsettlementhead.coaNo', $this->coaNo])
    	->andFilterWhere(['like', 'tr_clientsettlementhead.additionalInfo', $this->additionalInfo])
        ->andFilterWhere(['=', 'ms_status.statusID', $this->status]);
    	 
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['settlementDate' => SORT_DESC],
                'attributes' => ['settlementDate']
            ],
        ]);

        $dataProvider->sort->attributes['settlementNum'] = [
            'asc' => [self::tableName() . '.settlementNum' => SORT_ASC],
            'desc' => [self::tableName() . '.settlementNum' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['grandTotal'] = [
            'asc' => [self::tableName() . '.grandTotal' => SORT_ASC],
            'desc' => [self::tableName() . '.grandTotal' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['clientID'] = [
            'asc' => ['ms_client.clientName' => SORT_ASC],
            'desc' => ['ms_client.clientName' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['currencyID'] = [
            'asc' => ['ms_currency.currencyID' => SORT_ASC],
            'desc' => ['ms_currency.currencyID' => SORT_DESC],
        ];
        
        $dataProvider->sort->attributes['locationID'] = [
            'asc' => ['ms_location.locationName' => SORT_ASC],
            'desc' => ['ms_location.locationName' => SORT_DESC],
        ];
        
        $dataProvider->sort->attributes['additionalInfo'] = [
            'asc' => [self::tableName() . '.additionalInfo' => SORT_ASC],
            'desc' => [self::tableName() . '.additionalInfo' => SORT_DESC],
        ];
		
        $dataProvider->sort->attributes['coaNo'] = [
            'asc' => ['ms_coa.description' => SORT_ASC],
            'desc' => ['ms_coa.description' => SORT_DESC],
        ];
        
        $dataProvider->sort->attributes['status'] = [
            'asc' => ['ms_status.description' => SORT_ASC],
            'desc' => ['ms_status.description' => SORT_DESC],
        ];
        return $dataProvider;
    }
    
    public function afterFind() {
        parent::afterFind();
        $this->settlementDate = AppHelper::convertDateTimeFormat($this->settlementDate, 'Y-m-d H:i:s', 'd-m-Y');
         $this->joinClientSettlementDetail = [];
        $i = 0;
        foreach ($this->getClientsettlementdetails()->all() as $joinClientSettlementDetail) {
            $this->joinClientSettlementDetail[$i]["salesNum"] = $joinClientSettlementDetail->salesNum;
            $this->joinClientSettlementDetail[$i]["dueDate"] = $joinClientSettlementDetail->salesNum0->dueDate;
            $this->joinClientSettlementDetail[$i]["projectName"] ='';
            $this->joinClientSettlementDetail[$i]["outstanding"] = 0;
            $this->joinClientSettlementDetail[$i]["settlementTotal"] = $joinClientSettlementDetail->settlementTotal;
            $i += 1;
        }
    }

}
