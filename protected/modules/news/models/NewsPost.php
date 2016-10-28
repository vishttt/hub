<?php

namespace humhub\modules\news\models;

use Yii;
use humhub\components\ActiveRecord;
use humhub\models\Setting;
use humhub\modules\user\models\User;

/**
 * This is the model class for table "news_post".
 *
 * The followings are the available columns in table 'news_post':
 * @property integer $id
 * @property integer $category_id
 * @property string $metatags
 * @property string $title_url
 * @property string $title
 * @property string $content
 * @property string $created_at
 * @property string $updated_at
 * @property string $images
 * @property string $tags
 * @property integer $view
 * @property integer $parent_id
 * @property integer $created_by
 *
 * The followings are the available model relations:
 * @property Category[] $category
 * @property User[] $users
 *
 * @package humhub.modules.news.models
 * @since 0.5
 */
class NewsPost extends ActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'news_post';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(['created_by'], 'integer'),
            array(['title'], 'string', 'max' => 255),
            array(['created_at', 'updated_at'], 'safe'),
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
            'entries' => array(self::HAS_MANY, 'NewsPost', 'parent_id', 'order' => 'created_at ASC'),
            'users' => array(self::MANY_MANY, 'User', 'user_message(message_id, user_id)'),
            'originator' => array(self::BELONGS_TO, 'User', 'created_by'),
        );
    }

    public function getEntries()
    {
        $query = $this->hasMany(NewsPost::className(), ['parent_id' => 'id']);
        $query->addOrderBy(['created_at' => SORT_ASC]);
        return $query;
    }

    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
                        ->viaTable('user_message', ['message_id' => 'id']);
    }

    public function isParticipant($user)
    {
        foreach ($this->users as $participant) {
            if ($participant->guid === $user->guid) {
                return true;
            }
        }
        return false;
    }

    public function getOriginator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'category_id'=>Yii::t('Newsmodule.base','Category'),
            
            'title' => Yii::t('MailModule.base', 'Title'),
            'created_at' => Yii::t('MailModule.base', 'Created At'),
            'created_by' => Yii::t('MailModule.base', 'Created By'),
            'updated_at' => Yii::t('MailModule.base', 'Updated At'),
        );
    }

   
}
