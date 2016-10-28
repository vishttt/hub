<?php

namespace humhub\modules\news\models;

use Yii;
use humhub\components\ActiveRecord;
use humhub\models\Setting;
use humhub\modules\user\models\User;
use humhub\modules\news\models\MessageEntry;

/**
 * This is the model class for table "news_category".
 *
 * The followings are the available columns in table 'message':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property NewsPost[] $newPost
 *
 * @package humhub.modules.news.models
 * @since 0.5
 */
class Category extends ActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'news_category';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(['name'], 'string', 'max' => 255),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'entries' => array(self::HAS_MANY, 'NewsPost', 'category_id', 'order' => 'created_at ASC'),
        );
    }

    public function getEntries()
    {
        $query = $this->hasMany(MessageEntry::className(), ['message_id' => 'id']);
        $query->addOrderBy(['created_at' => SORT_ASC]);
        return $query;
    }

   

  

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => Yii::t('NewsModule.base', 'Title'),
        );
    }

 



}
