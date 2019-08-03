<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ms_payrolltaxbeforedetail".
 *
 * @property string $id
 * @property string $nomor
 * @property string $periodStart
 * @property string $periodEnd
 * @property string $npwpCompany
 * @property string $company
 * @property string $netto
 * @property string $taxPaid
 */
class MsPayrollTaxBeforeDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ms_payrolltaxbeforedetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['netto', 'taxPaid'], 'number'],
            [['id', 'nomor'], 'string', 'max' => 20],
            [['periodStart', 'periodEnd'], 'safe'],
            [['npwpCompany', 'company'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nomor' => 'Nomor',
            'periodStart' => 'Period Start',
            'periodEnd' => 'Period End',
            'npwpCompany' => 'Npwp Company',
            'company' => 'Company',
            'netto' => 'Netto',
            'taxPaid' => 'Tax Paid',
        ];
    }
}
