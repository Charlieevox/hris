<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;
/**
 * This is the model class for table "tr_budgetdetailmisc".
 *
 * @property integer $ID
 * @property integer $BHID
 * @property string $coaNo
 * @property string $totalCost
 */
class TrBudgetDetailMisc extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
         return Yii::$app->user->identity->dbName.'.tr_budgetdetailmisc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['BHID'], 'integer'],
            [['totalCost', 'qty', 'subTotal'], 'number'],
            [['coaNo'], 'string', 'max' => 50]
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
            'coaNo' => 'Coa No',
            'totalCost' => 'Total Cost',
        ];
    }
	
	 public function getCoaNos()
    {
        return $this->hasOne(MsCoa::className(), ['coaNo' => 'coaNo']);
    }
	
	public function getBudgetHeads()
    {
        return $this->hasOne(TrBudgetHead::className(), ['ID' => 'BHID']);
    }
}
