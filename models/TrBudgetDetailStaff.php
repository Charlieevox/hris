<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "tr_budgetdetailstaff".
 *
 * @property integer $ID
 * @property integer $BHID
 * @property integer $staffID
 * @property integer $length
 * @property string $estSalary
 * @property string $estBonus
 * @property string $totalCost
 */
class TrBudgetDetailStaff extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
         return Yii::$app->user->identity->dbName.'.tr_budgetdetailstaff';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['BHID', 'positionID'], 'integer'],
            [['length', 'totalCost', 'rate'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'BHID' => 'Bhid',
            'positionID' => 'Position Name',
            'length' => 'Length',
            'totalCost' => 'Total Cost',
			'rate' => 'Rate',
        ];
    }
	
	  public function getPositions()
    {
        return $this->hasOne(MsPosition::className(), ['positionID' => 'positionID']);
    }
    
    public function getTimePosition()
    {
        return $this->hasOne(LkTime::className(), ['timeID' => 'timeID'])->viaTable('ms_position', ['positionID' => 'positionID']);
    }
	
	public function getBudgetHeads()
    {
        return $this->hasOne(TrBudgetHead::className(), ['ID' => 'BHID']);
    }
}
