<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tr_leave".
 *
 * @property integer $id
 * @property integer $employeeId
 * @property integer $leaveId
 * @property string $startDate
 * @property string $endDate
 * @property string $notes
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 */

class TrLeave extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tr_leave';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'unique'],
            [['employeeId','leaveId','startDate','endDate'], 'required'],
            [['employeeId','leaveId'], 'integer'],
            [['startDate', 'endDate', 'createdDate', 'editedDate'], 'safe'],
            [['notes'], 'string', 'max' => 200],
            [['createdBy', 'editedBy'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'employeeId' => 'Employee Id',
            'leaveId' => 'Leave Id',
            'startDate' => 'Start Date',
            'endDate' => 'End Date',
            'notes' => 'Notes',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
        ];
    }

    public function search() {
    $query = self::find()
            ->andFilterWhere(['=', 'tr_leave.employeeId', $this->employeeId])
            ->andFilterWhere(['=', 'tr_leave.leaveId', $this->leaveId])
            ->andFilterWhere(['=', 'tr_leave.startDate', $this->startDate])
            ->andFilterWhere(['=', 'tr_leave.endDate', $this->endDate])
            ->andFilterWhere(['=', 'tr_leave.notes', $this->notes]);

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'sort' => [
            'defaultOrder' => ['startDate' => SORT_ASC],
            'attributes' => ['employeeId','leaveId','startDate','endDate']
        ],
    ]);

    return $dataProvider;
    }

    public function getPersonnelHead() {
        return $this->hasOne(MsPersonnelHead::className(), ['id' => 'employeeId']);
    }

    public function getLeaveDesc() {
        return $this->hasOne(LkLeave::className(), ['leaveId' => 'leaveId']);
    }

    
}
