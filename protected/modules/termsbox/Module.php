<?php

namespace humhub\modules\termsbox;

use Yii;
use yii\helpers\Url;

class Module extends \humhub\components\Module
{

    /**
     * On Init of Dashboard Sidebar, add the widget
     *
     * @param type $event
     */
    public static function onLayoutAddonsInit($event)
    {
        if (self::showTermsbox()) {
            $event->sender->addWidget(widgets\TermsboxModal::className(), [], ['sortOrder' => 99999]);
        }
    }

    public static function showTermsbox()
    {
        $settings = Yii::$app->getModule('termsbox')->settings;
        if(Yii::$app->user->isGuest || !$settings->get('active')) {
            return false;
        }

        $lastSeenTS = $settings->user()->get('timestamp');
        $currentTermsTS = $settings->get('timestamp');

        return $currentTermsTS != null && $lastSeenTS != $currentTermsTS;
    }


    public function getConfigUrl()
    {
        return Url::to(['/termsbox/admin/index']);
    }

}
