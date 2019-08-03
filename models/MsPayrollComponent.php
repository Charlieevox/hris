<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_payrollcomponent".
 *
 * @property string $payrollCode
 * @property string $type
 * @property string $parameter
 * @property string $payrollDesc
 * @property string $formula
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 */
class MsPayrollComponent extends \yii\db\ActiveRecord {

    public $articleDesc;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ms_payrollcomponent';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['payrollCode'], 'unique'],
            [['payrollCode','articleId','type'], 'required'],
            [['flagActive'], 'boolean'],
            [['createdDate', 'editedDate','articleDesc'], 'safe'],
            [['payrollCode'], 'string', 'max' => 20],
            [['type', 'parameter', 'payrollDesc', 'formula', 'createdBy', 'editedBy', 'articleId'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'payrollCode' => 'Payroll Code',
            'type' => 'Type',
            'parameter' => 'Parameter',
            'payrollDesc' => 'Payroll Desc',
            'formula' => 'Formula',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'flagActive' => 'Flag Active',
            'articleId' => 'Article Id'
        ];
    }

    public function search() {
        $query = self::find()
                ->andFilterWhere(['LIKE', 'ms_payrollcomponent.payrollCode', $this->payrollCode])
                ->andFilterWhere(['LIKE', 'ms_payrollcomponent.payrollDesc', $this->payrollDesc])
                ->andFilterWhere(['=', 'ms_payrollcomponent.parameter', $this->parameter])
                ->andFilterWhere(['=', 'ms_payrollcomponent.type', $this->type])
                ->andFilterWhere(['=', 'ms_payrollcomponent.flagActive', $this->flagActive])
                ->andFilterWhere(['<>', 'ms_payrollcomponent.type', 3]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['payrollCode' => SORT_ASC],
                'attributes' => ['flagActive', 'parameter', 'type', 'payrollDesc', 'payrollCode']
            ],
        ]);

        $dataProvider->sort->attributes['payrollCode'] = [
            'asc' => [self::tableName() . '.payrollCode' => SORT_ASC],
            'desc' => [self::tableName() . '.payrollCode' => SORT_DESC],
        ];
        return $dataProvider;
    }

    public function getMsSetting() {
        return $this->hasOne(MsSetting::className(), ['value1' => 'type'])->onCondition(['key1' => 'PayrollType']);
    }
    
    public function getTaxDesc() {
        return $this->hasOne(LkTaxArticle::className(), ['articleId' => 'articleId']);
    }

}
