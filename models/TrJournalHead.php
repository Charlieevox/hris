<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "tr_journalhead".
 *
 * @property integer $journalHeadID
 * @property string $journalDate
 * @property string $transactionType
 * @property string $refNum
 * @property string $notes
 * @property string $createdBy
 * @property string $createdDate
 *
 * @property TrJournaldetail[] $trJournaldetails
 */
class TrJournalHead extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
         return Yii::$app->user->identity->dbName.'.tr_journalhead';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['journalDate', 'transactionType', 'refNum', 'locationID', 'notes', 'createdBy', 'createdDate'], 'required'],
            [['journalDate', 'createdDate'], 'safe'],
             [['locationID'], 'integer'],
            [['transactionType', 'notes'], 'string', 'max' => 100],
            [['refNum', 'createdBy'], 'string', 'max' => 50],
			[['journalDate', 'transactionType', 'refNum', 'locationID'], 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'journalHeadID' => 'Journal Head ID',
            'journalDate' => 'Journal Date',
            'transactionType' => 'Transaction Type',
            'refNum' => 'Ref Num',
            'notes' => 'Notes',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'locationID' => 'Location Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrJournalDetails()
    {
        return $this->hasMany(TrJournalDetail::className(), ['journalHeadID' => 'journalHeadID']);
    }
}
