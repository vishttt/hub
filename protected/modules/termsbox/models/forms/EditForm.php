<?php

namespace humhub\modules\termsbox\models\forms;

use Yii;

class EditForm extends \yii\base\Model
{

    public $active;
    public $title;
    public $content;
    public $reset;
    public $statement;

    /**
     * @inheritdocs
     */
    public function rules()
    {
        return array(
            array(['title', 'content', 'statement'], 'required'),
            array(['reset', 'active'], 'safe')
        );
    }
    
    /**
     * @inheritdocs
     */
    public function init()
    {
        $settings = Yii::$app->getModule('termsbox')->settings;
        $this->title = $settings->get('title', Yii::t('TermsboxModule.models_forms_EditForm', 'Terms & Conditions'));
        $this->statement = $settings->get('statement', Yii::t('TermsboxModule.models_forms_EditForm', 'Please Read and Agree to our Terms & Conditions'));
        $this->content = $settings->get('content');
        $this->active = $settings->get('active', false);
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return array(
            'active' => Yii::t('TermsboxModule.models_forms_EditForm', 'Active'),
            'title' => Yii::t('TermsboxModule.models_forms_EditForm', 'Title'),
            'statement' => Yii::t('TermsboxModule.models_forms_EditForm', 'Statement'),
            'content' => Yii::t('TermsboxModule.models_forms_EditForm', 'Content'),
            'reset' => Yii::t('TermsboxModule.models_forms_EditForm', 'Mark as unseen for all users'),
        );
    }
    
    /**
     * Saves the given form settings.
     */
    public function save()
    {
        $settings = Yii::$app->getModule('termsbox')->settings;
        $settings->set('title', $this->title);
        $settings->set('statement', $this->statement);
        $settings->set('content', $this->content);
        
        if ($this->active) {
            $settings->set('active', true);
        } else {
            $settings->set('active', false);
        }
              
        // Note the reset flag only affects the timestamp if the conditionas are active
        $lastTimeStamp = $settings->get('timestamp');
        if ($this->active && ($this->reset || $lastTimeStamp == null)) {
            $settings->set('timestamp', time());
        }
        
        return true;
    }

}
