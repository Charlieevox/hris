<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tr_companybalance".
 *
 * @property integer $ID
 * @property integer $companyID
 * @property string $balanceDate
 * @property string $inAmount
 * @property string $outAmount
 */
class TrCompanyBalance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
         return Yii::$app->user->identity->dbName.'.tr_companybalance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['companyID', 'balanceDate', 'amount'], 'required'],
            [['companyID'], 'integer'],
            [['balanceDate'], 'safe'],
            [['amount'], 'number'],
			[['companyID'], 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'companyID' => 'Company Name',
            'balanceDate' => 'Balance Date',
            'amount' => 'Amount',
        ];
    }
	
	 public function getCompanies()
    {
        return $this->hasOne(MsCompany::className(), ['companyID' => 'companyID']);
    }
	
	 public function search()
    {
    	$query = self::find()
    	->joinWith('companies')
    	->andFilterWhere(['=', 'tr_companybalance.companyID', $this->companyID])
    	->andFilterWhere(['=', "DATE_FORMAT(tr_companybalance.balanceDate, '%d-%m-%Y')", $this->balanceDate])
    	->andFilterWhere(['=', 'tr_companybalance.amount', $this->amount]);
    	 
    	$dataProvider = new ActiveDataProvider([
    		'query' => $query,
    		'sort' => [
    			'defaultOrder' => ['balanceDate' => SORT_ASC],
    			'attributes' => ['balanceDate']
    		],
			'pagination' => [
				'pageSize' => 0,
				],
    	]);
        
    	$dataProvider->sort->attributes['amount'] = [
    		'asc' => [self::tableName() . '.amount' => SORT_ASC],
    		'desc' => [self::tableName() . '.amount' => SORT_DESC],
    	];
    
    	return $dataProvider;
    }
	
	 public function group()
    {
    	$query = self::find()
    	->select('tr_companybalance.companyID, SUM(tr_companybalance.amount) as amount')
    	->joinWith('companies')
    	->andFilterWhere(['=', 'tr_companybalance.companyID', $this->companyID])
    	->groupBy('tr_companybalance.companyID');
    	
    	$dataProvider = new ActiveDataProvider([
    		'query' => $query,
    		'sort' => [
    			'defaultOrder' => ['companyID' => SORT_ASC],
    			'attributes' => ['companyID']
    		],
    	]);
    		
    	$dataProvider->sort->attributes['amount'] = [
    		'asc' => ['amount' => SORT_ASC],
    		'desc' => ['amount' => SORT_DESC],
    	];
    	return $dataProvider;
    }
}
