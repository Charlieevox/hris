<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "ms_payrollincome".
 *
 * @property string $nik
 */
class MsPayrollIncome extends \yii\db\ActiveRecord {

    public $fullNameEmployee;
    public $joinPayrollIncomeDetail;
    public $joinPayrollIncomeDetail2;
    public $flag;
    public $joindate;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ms_payrollincome';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['nik'], 'required'],
            [['nik'], 'string', 'max' => 20],
            [['joinPayrollIncomeDetail','joinPayrollIncomeDetail2', 'fullNameEmployee', 'flag', 'joindate'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'nik' => 'Nik',
            'fullNameEmployee' => 'FullName',
            'joindate' => 'Join Date'
        ];
    }

    public function search() {
        $query = self::find()
                ->joinWith('personnelHead')
                ->andFilterWhere(['LIKE', 'ms_payrollincome.nik', $this->nik])
                ->andFilterWhere(['LIKE', 'ms_personnelHead.fullname', $this->fullNameEmployee]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['nik' => SORT_ASC],
                'attributes' => ['nik']
            ],
        ]);

        $dataProvider->sort->attributes['nik'] = [
            'asc' => [self::tableName() . '.nik' => SORT_ASC],
            'desc' => [self::tableName() . '.nik' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['fullNameEmployee'] = [
            'asc' => ['ms_personnelHead.fullname' => SORT_ASC],
            'desc' => ['ms_personnelHead.fullname' => SORT_DESC],
        ];

        return $dataProvider;
    }

    public function getPayrollIncomeDetail() {
        return $this->hasMany(MsPayrollIncomeDetail::className(), ['nik' => 'nik']);
    }

    public function getPersonnelHead() {
        return $this->hasOne(MsPersonnelHead::className(), ['id' => 'nik']);
    }
    
    public function getPersonnelContract() {
        return $this->hasMany(MsPersonnelContract::className(), ['nik' => 'nik'])
                    ->orderBy(['startDate' => SORT_ASC])
                    ->one();
    }

    public function afterFind() {
        parent::afterFind();
        $this->joinPayrollIncomeDetail = [];
        $i = 0;
        foreach ($this->getPayrollIncomeDetail()->where('flagActive="1"')->orderBy('payrollCode')->all() as $joinPayrollIncomeDetail) {
            $this->joinPayrollIncomeDetail[$i]["payrollCode"] = $joinPayrollIncomeDetail->payrollCode;
            $this->joinPayrollIncomeDetail[$i]["payrollType"] = $joinPayrollIncomeDetail->payrollType->msSetting->key2;
            $this->joinPayrollIncomeDetail[$i]["amount"] = $joinPayrollIncomeDetail->amount;
            $this->joinPayrollIncomeDetail[$i]["payrollDesc"] = $joinPayrollIncomeDetail->payrollComponentDesc->payrollDesc;
            $this->joinPayrollIncomeDetail[$i]["startdate"] = AppHelper::convertDateTimeFormat($joinPayrollIncomeDetail->startDate, 'Y-m-d', 'd-m-Y');
            $this->joinPayrollIncomeDetail[$i]["endDate"] = AppHelper::convertDateTimeFormat($joinPayrollIncomeDetail->endDate, 'Y-m-d', 'd-m-Y');
            $i += 1;
//            echo "<pre>";
//            var_dump($this->joinPayrollIncomeDetail[$i]["payrollCode"]);
//            echo "</pre>";
//            Yii::$app->end();
        }
    }

}
