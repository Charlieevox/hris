<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lk_accesscontrol".
 *
 * @property string $accessID
 * @property string $description
 * @property string $node
 * @property string $icon
 */
class LkAccessControl extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lk_accesscontrol';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['accessID', 'description', 'node', 'icon'], 'required'],
            [['accessID'], 'string', 'max' => 10],
            [['description', 'node', 'icon'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'accessID' => 'Access ID',
            'description' => 'Description',
            'node' => 'Node',
            'icon' => 'Icon',
        ];
    }
}
