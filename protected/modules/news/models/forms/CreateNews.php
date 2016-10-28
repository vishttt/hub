<?php

namespace humhub\modules\news\models\forms;

use Yii;
use yii\base\Model;
use humhub\modules\user\models\User;

/**
 * @package humhub.modules.news.forms
 * @since 0.5
 */
class CreateNews extends Model
{

    public $category;
    public $content;
    public $title;

    /**
     * Parsed recipients in array of user objects
     *
     * @var type
     */
    public $tags = array();

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array(['category', 'content', 'title'], 'required'),
            array('tags', 'checkTags')
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return array(
            'tags' => Yii::t('NewsModule.forms_CreateNewsForm', 'Tags'),
            'title' => Yii::t('NewsModule.forms_CreateNewsForm', 'Title'),
            'content' => Yii::t('NewsModule.forms_CreateNewsForm', 'Content'),
            'category' => Yii::t('NewsModule.forms_CreateNewsForm', 'Category'),

        );
    }

    /**
     * Form Validator which checks the recipient field
     *
     * @param type $attribute
     * @param type $params
     */
    public function checkTags($attribute, $params)
    {

//        // Check if email field is not empty
//        if ($this->$attribute != "") {
//
//            $recipients = explode(",", $this->$attribute);
//
//            foreach ($recipients as $userGuid) {
//                $userGuid = preg_replace("/[^A-Za-z0-9\-]/", '', $userGuid);
//
//                // Try load user
//                $user = User::findOne(['guid' => $userGuid]);
//                if ($user != null) {
//
//                    if ($user->id == Yii::$app->user->id) {
//                        $this->addError($attribute, Yii::t('NewsModule.forms_CreateMessageForm', "You cannot send a email to yourself!"));
//                    } else {
//                        $this->recipients[] = $user;
//                    }
//                }
//            }
//        }
    }

    /**
     * Returns an Array with selected recipients
     */
    public function getTags()
    {
        return $this->tags;
    }

}
