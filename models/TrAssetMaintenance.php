<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tr_assetmaintenance".
 *
 * @property integer $assetMaintenanceID
 * @property string $assetID
 * @property string $maintenanceValue
 * @property string $maintenanceDesc
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 */
class TrAssetMaintenance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_assetmaintenance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assetID', 'locationID', 'maintenanceDate','maintenanceValue', 'maintenanceDesc', 'createdBy', 'createdDate'], 'required'],
            [['maintenanceValue'], 'string'],
            [['locationID'], 'integer'],
            [['maintenanceDate','createdDate', 'editedDate'], 'safe'],
            [['assetID', 'createdBy', 'editedBy'], 'string', 'max' => 50],
            [['maintenanceDesc'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'assetMaintenanceID' => 'Asset Maintenance ID',
            'assetID' => 'Asset ID',
			'maintenanceDate' => 'Maintenance Date',
            'maintenanceValue' => 'Maintenance Value',
            'maintenanceDesc' => 'Maintenance Description',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'locationID' => 'Location Name',
        ];
    }
}
