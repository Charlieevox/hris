<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_taxrate".
 *
 * @property integer $tieringCode
 * @property string $start
 * @property string $end
 * @property string $npwpRate
 * @property string $nonNpwpRate
 */
class MsPayrollTaxRate extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ms_payrolltaxrate';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tieringCode'], 'required'],
            [['tieringCode'], 'safe'],
            [['start', 'end'], 'safe'],
            [['npwpRate', 'nonNpwpRate'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tieringCode' => 'Tiering Code',
            'start' => 'Start Amount',
            'end' => 'End Amount',
            'npwpRate' => 'NPWP Rate',
            'nonNpwpRate' => 'Non NPWP Rate',
        ];
    }

    public function search() {
        $query = self::find()
                ->andFilterWhere(['like', 'ms_payrolltaxrate.tieringCode', $this->tieringCode])
                ->andFilterWhere(['like', 'ms_payrolltaxrate.start', $this->start]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['tieringCode' => SORT_ASC],
                'attributes' => ['tieringCode', 'bankId']
            ],
        ]);

        $dataProvider->sort->attributes['tieringCode'] = [
            'asc' => [self::tableName() . '.tieringCode' => SORT_ASC],
            'desc' => [self::tableName() . '.tieringCode' => SORT_DESC],
        ];
        
        return $dataProvider;
    }

}
