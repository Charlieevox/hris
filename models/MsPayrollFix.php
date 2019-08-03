<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "ms_payrollfix".
 *
 * @property string $nik
 */
class MsPayrollFix extends \yii\db\ActiveRecord {

    public $joinPayrollFixDetail;
    public $fullNameEmployee;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ms_payrollfix';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['nik'], 'required'],
            [['nik'], 'string', 'max' => 20],
            [['joinPayrollFixDetail','fullNameEmployee'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'nik' => 'Nik',
            'fullNameEmployee' => 'FullName',
        ];
    }

    public function search() {
        $query = self::find()
                ->joinWith('personnelHead')
                ->andFilterWhere(['LIKE', 'ms_payrollfix.nik', $this->nik])
                ->andFilterWhere(['LIKE', 'ms_personnelHead.fullname', $this->fullNameEmployee]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['nik' => SORT_ASC],
                'attributes' => ['nik','fullNameEmployee']
            ],
        ]);

        $dataProvider->sort->attributes['nik'] = [
            'asc' => [self::tableName() . '.nik' => SORT_ASC],
            'desc' => [self::tableName() . '.nik' => SORT_DESC],
        ];

        return $dataProvider;
    }

    public function getPayrollFixDetail() {
        return $this->hasMany(MsPayrollFixDetail::className(), ['nik' => 'nik']);
    }
    
        public function getPersonnelHead() {
        return $this->hasOne(MsPersonnelHead::className(), ['id' => 'nik']);
    }

    public function afterFind() {
        parent::afterFind();
        $this->joinPayrollFixDetail = [];
        $i = 0;
        foreach ($this->getPayrollFixDetail()->all() as $joinPayrollFixDetail) {
            $this->joinPayrollFixDetail[$i]["payrollCode"] = $joinPayrollFixDetail->payrollCode;
            $this->joinPayrollFixDetail[$i]["amount"] = $joinPayrollFixDetail->amount;
            $this->joinPayrollFixDetail[$i]["payrollDesc"] = $joinPayrollFixDetail->payrollComponentDesc->payrollDesc;
            $i += 1;
        }
    }

}
