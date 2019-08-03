<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tr_journaldetail".
 *
 * @property integer $journalDetailID
 * @property integer $journalHeadID
 * @property string $coaNo
 * @property string $currency
 * @property string $rate
 * @property string $drAmount
 * @property string $crAmount
 *
 * @property TrJournalhead $journalHead
 */
class TrJournalDetail extends \yii\db\ActiveRecord
{
	public $transactionType;
	public $refNum;
	public $journalDate;
        public $locationID;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_journaldetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['journalHeadID', 'coaNo', 'currency', 'rate', 'drAmount', 'crAmount'], 'required'],
            [['journalHeadID'], 'integer'],
            [['rate', 'drAmount', 'crAmount'], 'number'],
            [['coaNo'], 'string', 'max' => 20],
            [['currency'], 'string', 'max' => 10],
			[['journalDate','transactionType','refNum','coaNo','drAmount','crAmount','journalHeadID','locationID'], 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'journalDetailID' => 'Journal Detail ID',
            'journalHeadID' => 'Journal Head ID',
            'coaNo' => 'Description',
            'currency' => 'Currency',
            'rate' => 'Rate',
            'drAmount' => 'Debit Amount',
            'crAmount' => 'Credit Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJournalHeads()
    {
        return $this->hasOne(TrJournalHead::className(), ['journalHeadID' => 'journalHeadID']);
    }
	
	 public function getCoaNos()
    {
        return $this->hasOne(MsCoa::className(), ['coaNo' => 'coaNo']);
    }
	
	public function search()
    {
        $query = self::find()
			->joinWith('journalHeads')
			->joinWith('coaNos')
            ->andFilterWhere(['like', 'tr_journalhead.transactionType', $this->transactionType])
			->andFilterWhere(['=', "DATE_FORMAT(tr_journalhead.journalDate, '%d-%m-%Y')", $this->journalDate])
			->andFilterWhere(['like', 'tr_journalhead.refNum', $this->refNum])
			->andFilterWhere(['=', 'tr_journaldetail.coaNo', $this->coaNo])
                        ->andFilterWhere(['=', 'tr_journaldetail.journalHeadID', $this->journalHeadID])
			->andFilterWhere(['=', 'tr_journalhead.locationID', $this->locationID])
			->andFilterWhere(['=', 'tr_journaldetail.drAmount', $this->drAmount])
			->andFilterWhere(['=', 'tr_journaldetail.crAmount', $this->crAmount]);
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['journalHeadID' => SORT_DESC],
                'attributes' => ['journalHeadID']
            ],
			'pagination' => [
				'pageSize' => 0,
				],
        ]);
		
		$dataProvider->sort->attributes['transactionType'] = [
            'asc' => ['tr_journalhead.transactionType' => SORT_ASC],
            'desc' => ['tr_journalhead.transactionType' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['journalDate'] = [
            'asc' => ['tr_journalhead.journalDate' => SORT_ASC],
            'desc' => ['tr_journalhead.journalDate' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['refNum'] = [
            'asc' => ['tr_journalhead.refNum' => SORT_ASC],
            'desc' => ['tr_journalhead.refNum' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['coaNo'] = [
            'asc' => ['ms_coa.description' => SORT_ASC],
            'desc' => ['ms_coa.description' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['drAmount'] = [
            'asc' => [self::tableName() . '.drAmount' => SORT_ASC],
            'desc' => [self::tableName() . '.drAmount' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['crAmount'] = [
            'asc' => [self::tableName() . '.crAmount' => SORT_ASC],
            'desc' => [self::tableName() . '.crAmount' => SORT_DESC],
        ];
        return $dataProvider;
    }
	
}
