<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_payrollfunctionalexpenses".
 *
 * @property integer $id
 * @property string $rate
 * @property string $maxAmount
 */
class MsPayrollFunctionalExpenses extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ms_payrollfunctionalexpenses';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['rate', 'maxAmount','editedDate'], 'safe'],
            [['editedBy'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'rate' => 'Rate',
            'maxAmount' => 'Max Amount',
        ];
    }

}
