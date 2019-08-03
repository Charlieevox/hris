<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ms_personnelfamily".
 *
 * @property integer $nik
 * @property string $firstName
 * @property string $lastName
 * @property string $relationship
 * @property string $idNumber
 * @property string $birthPlace
 * @property string $birthDate
 */
class MsPersonnelFamily extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ms_personnelfamily';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['birthDate'], 'safe'],
            [['firstName', 'lastName'], 'string', 'max' => 30],
            [['relationship', 'idNumber'], 'string', 'max' => 20],
            [['birthPlace'], 'string', 'max' => 25]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Nik',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'relationship' => 'Relationship',
            'idNumber' => 'Id Number',
            'birthPlace' => 'Birth Place',
            'birthDate' => 'Birth Date',
        ];
    }
}
