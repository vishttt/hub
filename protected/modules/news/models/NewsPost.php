<?php

namespace humhub\modules\news\models;

use Yii;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\search\interfaces\Searchable;


/**
 * This is the model class for table "news_post".
 *
 * The followings are the available columns in table 'news_post':
 * @property integer $id
 * @property string $message_2trash
 * @property string $message
 * @property string $url
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $category_id
 * @property integer $views
 * @property integer $parent_id
 * @property integer $primary
 *
 * The followings are the available model relations:
 * @property Category[] $category
 * @property User[] $users
 *
 * @package humhub.modules.news.models
 * @since 0.5
 */
class NewsPost extends ContentActiveRecord implements Searchable
{

        public $wallEntryClass = "humhub\modules\news\widgets\WallEntry";

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
                  [['message'], 'required'],
            [['message'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['url'], 'string', 'max' => 255]
        );
    }
public function beforeSave($insert)
    {
        // Prebuild Previews for URLs in Message
        \humhub\models\UrlOembed::preload($this->message);

        // Check if Post Contains an Url
        if (preg_match('/http(.*?)(\s|$)/i', $this->message)) {
            // Set Filter Flag
            $this->url = 1;
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {

        parent::afterSave($insert, $changedAttributes);

        // Handle mentioned users
        \humhub\modules\user\models\Mentioning::parse($this, $this->message);

        return true;
    }

 public function getContentName()
    {
        return Yii::t('PostModule.models_Post', 'post');
    }

    /**
     * @inheritdoc
     */
    public function getContentDescription()
    {
        return $this->message;
    }

    /**
     * @inheritdoc
     */
    public function getSearchAttributes()
    {
        $attributes = array(
            'message' => $this->message,
            'url' => $this->url,
        );

        $this->trigger(self::EVENT_SEARCH_ADD, new \humhub\modules\search\events\SearchAddEvent($attributes));

        return $attributes;
    }


    /**
     * @return array customized attribute labels (name=>label)
     */
   public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message' => 'Message',
            'url' => 'Url',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

   
}
