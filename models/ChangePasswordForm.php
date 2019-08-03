<?php

namespace app\models;

use Yii;
use yii\base\Model;

class ChangePasswordForm extends Model
{
    public $currentPassword;
    public $newPassword;
    public $repeatPassword;

    public function rules()
    {
        return [
            [['currentPassword', 'newPassword', 'repeatPassword'], 'required'],
            ['repeatPassword', 'compare', 'compareAttribute' => 'newPassword'],
            ['currentPassword', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'currentPassword' => 'Current Password',
            'newPassword' => 'New Password',
            'repeatPassword' => 'Repeat New Password'
        ];
    }

    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = Yii::$app->user->identity;
            if (!$user->validatePassword($this->currentPassword)) {
                $this->addError($attribute, $this->getAttributeLabel($attribute) . ' incorrect');
            }
        }
    }

    public function save()
    {
        if ($this->validate()) {
            $user = Yii::$app->user->identity;
            $user->password_input = $this->newPassword;
            return $user->save();
        }
        return false;
    }
}
