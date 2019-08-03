<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_personneljamsostek".
 *
 * @property string $jamsostekCode
 * @property string $jkkCom
 * @property string $jkkEmp
 * @property string $maxRateJkk
 * @property string $jkmCom
 * @property string $jkmEmp
 * @property string $maxRateJkm
 * @property string $jhtCom
 * @property string $jhtEmp
 * @property string $maxRateJht
 * @property string $jpkCom
 * @property string $jpkEmp
 * @property string $maxRateJpk
 * @property string $jpnCom
 * @property string $jpnEmp
 * @property string $maxRateJpn
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 * @property boolean $flagActive
 */
class MsPayrollJamsostek extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ms_payrolljamsostek';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['jamsostekCode'], 'unique'],
            [['jamsostekCode'], 'required'],
            [['jkkCom', 'jkkEmp', 'maxRateJkk', 'jkmCom', 'jkmEmp', 'maxRateJkm', 'jhtCom', 'jhtEmp', 'maxRateJht', 'jpkCom', 'jpkEmp', 'maxRateJpk', 'jpnCom', 'jpnEmp', 'maxRateJpn'], 'safe'],
            [['createdDate', 'editedDate'], 'safe'],
            [['flagActive'], 'boolean'],
            [['payrollCodeSource'], 'string', 'max' => 20],
            [['jamsostekCode', 'createdBy', 'editedBy'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'jamsostekCode' => 'Jamsostek Code',
            'payrollCodeSource' => 'Payroll Code',
            'jkkCom' => 'JKK Company',
            'jkkEmp' => 'JKK Employee',
            'maxRateJkk' => 'Max Rate JKK',
            'jkmCom' => 'JK Company',
            'jkmEmp' => 'JK Employee',
            'maxRateJkm' => 'Max Rate JK',
            'jhtCom' => 'JHT Company',
            'jhtEmp' => 'JHT Employee',
            'maxRateJht' => 'Max Rate JHT',
            'jpkCom' => 'BPJSK Company',
            'jpkEmp' => 'BPJSK Employee',
            'maxRateJpk' => 'Max Rate BPJSK',
            'jpnCom' => 'JP Company',
            'jpnEmp' => 'JP Employee',
            'maxRateJpn' => 'Max Rate JP',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'flagActive' => 'Flag Active',
        ];
    }

    public function search() {
        $query = self::find()
                ->andFilterWhere(['like', 'ms_payrolljamsostek.jamsostekCode', $this->jamsostekCode])
                ->andFilterWhere(['like', 'ms_payrolljamsostek.jkkCom', $this->jkkCom])
                ->andFilterWhere(['like', 'ms_payrolljamsostek.jkkEmp', $this->jkkEmp])
                ->andFilterWhere(['like', 'ms_payrolljamsostek.maxRateJkk', $this->maxRateJkk])
                ->andFilterWhere(['like', 'ms_payrolljamsostek.jkmCom', $this->jkmCom])
                ->andFilterWhere(['=', 'ms_payrolljamsostek.flagActive', $this->flagActive]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['jamsostekCode' => SORT_ASC],
                'attributes' => ['jamsostekCode','jkkCom','jkkEmp','maxRateJkk','jkmCom']
            ],
        ]);

        $dataProvider->sort->attributes['jamsostekCode'] = [
            'asc' => [self::tableName() . '.jamsostekCode' => SORT_ASC],
            'desc' => [self::tableName() . '.jamsostekCode' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['flagActive'] = [
            'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
            'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
        ];
        return $dataProvider;
    }

}
