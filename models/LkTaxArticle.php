<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lk_taxarticle".
 *
 * @property string $articleId
 * @property string $articleDesc
 */
class LkTaxArticle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lk_taxarticle';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['articleId'], 'required'],
            [['articleId', 'articleDesc'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'articleId' => 'Article ID',
            'articleDesc' => 'Article Desc',
        ];
    }
}
