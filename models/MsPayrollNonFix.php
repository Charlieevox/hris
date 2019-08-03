<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "ms_payrollnonfix".
 *
 * @property string $nik
 */
class MsPayrollNonFix extends \yii\db\ActiveRecord {

    public $joinPayrollNonFixDetail;
    public $fullNameEmployee;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ms_payrollnonfix';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['nik'], 'required'],
            [['nik'], 'string', 'max' => 20],
            [['joinPayrollNonFixDetail', 'fullNameEmployee'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'nik' => 'NIK',
            'fullNameEmployee' => 'FullName',
        ];
    }

    public function search() {
        $query = self::find()
                ->joinWith('personnelHead')
                ->andFilterWhere(['LIKE', 'ms_payrollnonfix.nik', $this->nik])
                ->andFilterWhere(['LIKE', 'ms_personnelHead.fullname', $this->fullNameEmployee]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['nik' => SORT_ASC],
                'attributes' => ['nik', 'fullNameEmployee']
            ],
        ]);

        $dataProvider->sort->attributes['nik'] = [
            'asc' => [self::tableName() . '.nik' => SORT_ASC],
            'desc' => [self::tableName() . '.nik' => SORT_DESC],
        ];

        return $dataProvider;
    }

    public function getPayrollNonFixDetail() {
        return $this->hasMany(MsPayrollNonFixDetail::className(), ['nik' => 'nik']);
    }

    public function getPersonnelHead() {
        return $this->hasOne(MsPersonnelHead::className(), ['id' => 'nik']);
    }

    public function afterFind() {
        parent::afterFind();
        $this->joinPayrollNonFixDetail = [];
        $i = 0;
        foreach ($this->getPayrollNonFixDetail()->all() as $joinPayrollNonFixDetail) {
            $this->joinPayrollNonFixDetail[$i]["period"] = $joinPayrollNonFixDetail->period;
            $this->joinPayrollNonFixDetail[$i]["payrollCode"] = $joinPayrollNonFixDetail->payrollCode;
            $this->joinPayrollNonFixDetail[$i]["amount"] = $joinPayrollNonFixDetail->amount;
            $this->joinPayrollNonFixDetail[$i]["payrollDesc"] = $joinPayrollNonFixDetail->payrollComponentDesc->payrollDesc;
            $i += 1;
        }
    }

}
