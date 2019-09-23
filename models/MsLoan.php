<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "ms_loan".
 *
 * @property integer $id
 * @property string $nik
 * @property string $registrationDate
 * @property string $principal
 * @property integer $term
 * @property string $downPayment
 * @property string $principalPaid
 * @property string $remarks
 * @property boolean $flagActive
 */
class MsLoan extends \yii\db\ActiveRecord {

    public $fullNameEmployee;
    public $joinTrLoanProc;
    public $manualPrincipalPeriod;
    public $manualPrincipalPaid;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ms_loan';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['registrationPeriod', 'principal', 'term'], 'required'],
            [['id'], 'integer'],
            [['registrationPeriod', 'joinTrLoanProc'], 'safe'],
            [['createdDate', 'editedDate','principalPaidMonthly'], 'safe'],
            [['principal', 'downPayment', 'principalPaid', 'fullNameEmployee','manualPrincipalPaid','manualPrincipalPeriod'], 'safe'],
            [['term'], 'integer'],
            [['flagActive'], 'boolean'],
            [['createdBy', 'editedBy'], 'string', 'max' => 50],
            [['nik', 'remarks'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'Loan ID',
            'fullNameEmployee' => 'FullName',
            'nik' => 'Nik',
            'registrationPeriod' => 'Registration Period',
            'principal' => 'Principal',
            'term' => 'Term',
            'downPayment' => 'Down Payment',
            'principalPaid' => 'Principal Paid',
            'principalPaidMonthly' => 'Principal Paid Monthly',
            'manualPrincipalPaid' => 'Principal Period',
            'manualPrincipalPeriod' => 'Principal Period',
            'remarks' => 'Remarks',
            'flagActive' => 'Flag Active',
        ];
    }

    public function search() {
        $query = self::find()
                ->joinWith('personnelHead')
                ->andFilterWhere(['like', 'ms_loan.id', $this->id])
                ->andFilterWhere(['like', 'ms_loan.nik', $this->nik])
                ->andFilterWhere(['=', 'ms_loan.flagActive', $this->flagActive])
                ->andFilterWhere(['LIKE', 'ms_personnelHead.fullname', $this->fullNameEmployee]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_ASC],
                'attributes' => ['id', 'nik', 'fullNameEmployee', 'registrationPeriod', 'principal', 'term']
            ],
        ]);

        $dataProvider->sort->attributes['nik'] = [
            'asc' => [self::tableName() . '.fullNameEmployee' => SORT_ASC],
            'desc' => [self::tableName() . '.fullNameEmployee' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['fullNameEmployee'] = [
            'asc' => ['ms_personnelHead.fullname' => SORT_ASC],
            'desc' => ['ms_personnelHead.fullname' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['flagActive'] = [
            'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
            'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
        ];
        return $dataProvider;
    }

    public function getPersonnelHead() {
        return $this->hasOne(MsPersonnelHead::className(), ['id' => 'nik']);
    }

    public function getTrLoanProc() {
        return $this->hasMany(TrLoanProc::className(), ['id' => 'id']);
    }

    public function afterFind() {
        parent::afterFind();
        $this->joinTrLoanProc = [];
        $i = 0;
        foreach ($this->getTrLoanProc()->all() as $joinTrLoanProc) {
            $this->joinTrLoanProc[$i]["paymentPeriod"] = $joinTrLoanProc->paymentPeriod;
            $this->joinTrLoanProc[$i]["principalPaid"] = $joinTrLoanProc->principalPaid;
            $i += 1;
        }
    }

}
