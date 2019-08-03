<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "ms_payrolltaxbefore".
 *
 * @property string $id
 * @property integer $nik
 * @property string $year
 */
class MsPayrollTaxBefore extends \yii\db\ActiveRecord
{
	 public $joinPayrollTaxIncomeDetail;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ms_payrolltaxbefore';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['nik'], 'integer'],
			[['joinPayrollTaxIncomeDetail'],'safe'],
            [['id', 'year'], 'string', 'max' => 45],
			[['createdBy', 'editedBy' ], 'string', 'max' => 45],
            [['createdDate','editedDate'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nik' => 'Nik',
            'year' => 'Year',
        ];
    }
	
	public function search() {
        $query = self::find()
                ->andFilterWhere(['like', 'ms_payrolltaxbefore.year', $this->year])
                ->andFilterWhere(['like', 'ms_payrolltaxbefore.nik', $this->nik]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['nik' => SORT_ASC],
                'attributes' => ['nik','year']
            ],
        ]);

        $dataProvider->sort->attributes['nik'] = [
            'asc' => [self::tableName() . '.nik' => SORT_ASC],
            'desc' => [self::tableName() . '.nik' => SORT_DESC],
        ];

        return $dataProvider;
    }
	
	public function getTaxIncomeDetail()
    {
        return $this->hasMany(MsPayrollTaxBeforeDetail::className(), ['id' => 'id']);
    }
    
    
    public function getPersonnelHead() {
        return $this->hasOne(MsPersonnelHead::className(), ['id' => 'nik']);
    }
	
	public function afterFind() {
        parent::afterFind();
        $this->joinPayrollTaxIncomeDetail = [];
        $i = 0;
        foreach ($this->getTaxIncomeDetail()->all() as $joinPayrollTaxIncomeDetail) {
            $this->joinPayrollTaxIncomeDetail[$i]["actionNumber"] = $joinPayrollTaxIncomeDetail->nomor;
			$this->joinPayrollTaxIncomeDetail[$i]["actionStartDate"] = AppHelper::convertDateTimeFormat($joinPayrollTaxIncomeDetail->periodStart, 'Y-m-d', 'd-m-Y');
            $this->joinPayrollTaxIncomeDetail[$i]["actionEndDate"] = AppHelper::convertDateTimeFormat($joinPayrollTaxIncomeDetail->periodEnd, 'Y-m-d', 'd-m-Y');
            $this->joinPayrollTaxIncomeDetail[$i]["actionNPWPCompany"] = $joinPayrollTaxIncomeDetail->npwpCompany;
            $this->joinPayrollTaxIncomeDetail[$i]["actionCompany"] = $joinPayrollTaxIncomeDetail->company;
			$this->joinPayrollTaxIncomeDetail[$i]["actionNetto"] = $joinPayrollTaxIncomeDetail->netto;
			$this->joinPayrollTaxIncomeDetail[$i]["actionTaxPaid"] = $joinPayrollTaxIncomeDetail->taxPaid;			
            $i += 1;
        }
    }
	
}
