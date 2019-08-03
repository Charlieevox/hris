<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ms_medicalincomedetail".
 *
 * @property integer $id
 * @property string $claimDate
 * @property string $claimType
 * @property string $inAmount
 * @property string $outAmount
 * @property string $notes
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 * @property string $flagActive
 */
class MsMedicalIncomeDetail extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ms_medicalincomedetail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'integer'],
            [['claimDate', 'createdDate', 'editedDate'], 'safe'],
            [['inAmount', 'outAmount'], 'number'],
            [['claimType'], 'string', 'max' => 20],
            [['notes', 'createdBy', 'editedBy', 'flagActive'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'claimDate' => 'Claim Date',
            'claimType' => 'Claim Type',
            'inAmount' => 'In Amount',
            'outAmount' => 'Out Amount',
            'notes' => 'Notes',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'flagActive' => 'Flag Active',
        ];
    }

    public function getMedicalType() {
        return $this->hasOne(MsMedicalType::className(), ['id' => 'claimType']);
    }

}
