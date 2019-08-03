<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_personnelptkp".
 *
 * @property integer $id
 * @property string $ptkpCode
 * @property string $ptkpDesc
 * @property integer $rate
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedDate
 * @property string $editedBy
 * @property boolean $flagActive
 */
class MsPayrollPtkp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ms_payrollptkp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rate','ptkp'], 'safe'],
            [['editedDate'], 'safe'],
            [['editedBy'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rate' => 'Tax Rate Dependents',
            'ptkp'=> 'PTKP',
            'editedDate' => 'Edited Date',
            'editedBy' => 'Edited By',
            'flagActive' => 'Flag Active',
        ];
    }
}
