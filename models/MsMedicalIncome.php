<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "ms_medicalincome".
 *
 * @property integer $id
 * @property string $nik
 * @property string $period
 * @property string $amount
 */
class MsMedicalIncome extends \yii\db\ActiveRecord {

    public $joinMedicalIncomeDetail;
    public $fullNameEmployee;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ms_medicalincome';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['period'], 'required'],
            [['nik', 'period', 'amount'], 'string', 'max' => 45],
            [['flagActive'], 'boolean'],
            [['joinMedicalIncomeDetail','fullNameEmployee'], 'safe'],
            [['id','nik','period'], 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'nik' => 'Nik',
            'period' => 'Period',
            'amount' => 'Balance',
            'fullNameEmployee' => 'FullName',
        ];
    }

    public function search() {
        $query = self::find()
                ->joinWith('personnelHead')
                ->andFilterWhere(['like', 'ms_medicalincome.nik', $this->nik])
                ->andFilterWhere(['like', 'ms_medicalincome.Period', $this->period])
                ->andFilterWhere(['like', 'ms_medicalincome.amount', $this->amount])
                ->andFilterWhere(['LIKE', 'ms_personnelHead.fullname', $this->fullNameEmployee]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['nik' => SORT_ASC],
                'attributes' => ['nik', 'period', 'amount','fullNameEmployee']
            ],
        ]);

        return $dataProvider;
    }
    
    public function getPersonnelHead() {
        return $this->hasOne(MsPersonnelHead::className(), ['id' => 'nik']);
    }

    public function getMedicalIncomeDetail() {
        return $this->hasMany(MsMedicalIncomeDetail::className(), ['id' => 'id']);
    }

    public function getMedicalTypeJoin() {
        return $this->hasOne(MsMedicalType::className(), ['id' => 'claimType']);
    }
    
    public function afterFind() {
        parent::afterFind();
        $this->joinMedicalIncomeDetail = [];
        $i = 0;
        foreach ($this->getMedicalIncomeDetail()->all() as $joinMedicalIncomeDetail) {
            $this->joinMedicalIncomeDetail[$i]["claimDate"] = AppHelper::convertDateTimeFormat($joinMedicalIncomeDetail->claimDate, 'Y-m-d', 'd-m-Y');
            $this->joinMedicalIncomeDetail[$i]["claimType"] = $joinMedicalIncomeDetail->claimType;
            $this->joinMedicalIncomeDetail[$i]["claimTypeDesc"] = $joinMedicalIncomeDetail->medicalType->typeDescription;
            $this->joinMedicalIncomeDetail[$i]["inAmount"] = $joinMedicalIncomeDetail->inAmount;
            $this->joinMedicalIncomeDetail[$i]["outAmount"] = $joinMedicalIncomeDetail->outAmount;
            $this->joinMedicalIncomeDetail[$i]["notes"] = $joinMedicalIncomeDetail->notes;
            $i += 1;
//            echo "<pre>";
//            var_dump($this->joinMedicalIncomeDetail);
//            echo "</pre>";
//            Yii::$app->end();
        }
    }

}
