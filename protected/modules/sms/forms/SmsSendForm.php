<?php

namespace humhub\modules\sms\forms;

class SmsSendForm extends \yii\base\Model
{

    public $message;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('message', 'required'),
            array('message', 'string', 'max' => 230),
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
        );
    }

}
