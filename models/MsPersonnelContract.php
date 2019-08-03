<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ms_personnelcontract".
 *
 * @property integer $nik
 * @property string $startDate
 * @property string $endDate
 * @property string $docNo
 */
class MsPersonnelContract extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ms_personnelcontract';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nik'], 'required'],
            [['nik'], 'integer'],
            [['startDate','startWorking', 'endDate'], 'safe'],
            [['docNo','status','position'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nik' => 'Nik',
			'startWorking' => 'Start Working',
            'startDate' => 'Start Date',
            'endDate' => 'End Date',
            'docNo' => 'Doc No',
        ];
    }
	
	public function getPositiondesc() {
        return $this->hasOne(MsPersonnelPosition::className(), ['id' => 'position']);
    }
}
