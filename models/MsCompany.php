<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_company".
 *
 * @property integer $companyID
 * @property string $companyName
 * @property string $companyAddress
 * @property string $prorateSetting
 * @property string $taxSetting
 */
class MsCompany extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ms_company';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['companyName'], 'required'],
            [['companyName'], 'string', 'max' => 100],
            [['createdDate', 'editedDate','dateStart','dateEnd'], 'safe'],
            [['createdBy', 'editedBy'], 'string', 'max' => 50],
            [['overMonth','incHolidayDate'], 'boolean'],
            [['companyAddress', 'prorateSetting', 'taxSetting','startPayrollPeriod'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'companyID' => 'Company ID',
            'companyName' => 'Company Name',
            'companyAddress' => 'Company Address',
            'prorateSetting' => 'Prorate Setting',
            'taxSetting' => 'Tax Setting',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'dateStart' => 'Start Date Cut Off',
            'dateEnd' => 'End Date Cut Off',
            'startPayrollPeriod' => 'Start Payroll Period',
            'incHolidayDate' => 'Using Holiday Day'
        ];
    }

    public function search() {
        $query = self::find()
                ->andFilterWhere(['like', 'ms_company.companyAddress', $this->companyAddress])
                ->andFilterWhere(['=', 'ms_company.prorateSetting', $this->prorateSetting])
                ->andFilterWhere(['=', 'ms_company.taxSetting', $this->taxSetting])
                ->andFilterWhere(['like', 'ms_company.companyName', $this->companyName]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['companyName' => SORT_ASC],
                'attributes' => ['companyName','companyAddress','prorateSetting','taxSetting']
            ],
        ]);

        $dataProvider->sort->attributes['companyName'] = [
            'asc' => [self::tableName() . '.companyName' => SORT_ASC],
            'desc' => [self::tableName() . '.companyName' => SORT_DESC],
        ];
        
        return $dataProvider;
    }

}
