<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lk_filteraccess".
 *
 * @property string $accessID
 * @property boolean $insertAcc
 * @property boolean $updateAcc
 * @property boolean $deleteAcc
 * @property boolean $authorizeAcc
 *
 * @property MsUseraccess[] $msUseraccesses
 */
class LkFilterAccess extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lk_filteraccess';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['accessID'], 'required'],
            [['insertAcc', 'updateAcc', 'deleteAcc', 'authorizeAcc'], 'boolean'],
            [['accessID'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'accessID' => 'Access ID',
            'insertAcc' => 'Insert Acc',
            'updateAcc' => 'Update Acc',
            'deleteAcc' => 'Delete Acc',
            'authorizeAcc' => 'Authorize Acc',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMsUseraccesses()
    {
        return $this->hasMany(MsUseraccess::className(), ['accessID' => 'accessID']);
    }
	
	 public function getAccessControls()
    {
        return $this->hasMany(LkAccessControl::className(), ['accessID' => 'accessID']);
    }
}
