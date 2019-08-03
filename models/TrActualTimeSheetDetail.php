<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tr_actualtimesheetdetail".
 *
 * @property integer $actualTimesheetDetailID
 * @property string $actualTimesheetNum
 * @property string $timeQty
 * @property integer $customerID
 * @property string $description
 *
 * @property MsCustomer $customer
 * @property TrActualTimeSheetHead $actualTimesheetNum0
 */
class TrActualTimeSheetDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_actualtimesheetdetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['actualTimesheetNum', 'timeQty', 'clientID', 'description'], 'required'],
            [['timeQty'], 'number'],
            [['clientID'], 'integer'],
            [['actualTimesheetNum'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'actualTimesheetDetailID' => 'Actual Timesheet Detail ID',
            'actualTimesheetNum' => 'Actual Timesheet Num',
            'timeQty' => 'Time Qty',
            'clientID' => 'Client',
            'description' => 'Description',
        ];
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
    public function getActualTimesheetNum()
    {
        return $this->hasOne(TrActualTimeSheetHead::className(), ['actualTimesheetNum' => 'actualTimesheetNum']);
    }
}
