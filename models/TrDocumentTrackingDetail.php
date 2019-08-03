<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tr_documenttrackingdetail".
 *
 * @property integer $documentTrackingDetailID
 * @property string $documentTrackingNum
 * @property string $actionDate
 * @property string $actionDesc
 * @property string $actionBy
 * @property string $createdBy
 *
 * @property TrDocumenttrackinghead $documentTrackingNum0
 */
class TrDocumentTrackingDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_documenttrackingdetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['documentTrackingNum', 'actionDate', 'actionDesc', 'actionBy', 'createdBy'], 'required'],
            [['actionDate'], 'safe'],
            [['documentTrackingNum', 'actionBy', 'createdBy'], 'string', 'max' => 50],
            [['actionDesc'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'documentTrackingDetailID' => 'Document Tracking Detail ID',
            'documentTrackingNum' => 'Document Tracking Num',
            'actionDate' => 'Action Date',
            'actionDesc' => 'Action Desc',
            'actionBy' => 'Action By',
            'createdBy' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentTrackingNum0()
    {
        return $this->hasOne(TrDocumenttrackinghead::className(), ['documentTrackingNum' => 'documentTrackingNum']);
    }
}
