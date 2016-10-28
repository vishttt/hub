<?php

namespace humhub\modules\termsbox\controllers;

use Yii;
use humhub\components\Controller;

class IndexController extends Controller
{

    /**
     * Configuration Action for Super Admins
     */
    public function actionAccept()
    {
        $settings = Yii::$app->getModule('termsbox')->settings;
        $settings->user()->set('timestamp', $settings->get('timestamp'));
        Yii::$app->response->format = 'json';
        return ['success' => true];
    }
    
    public function actionDecline()
    {
        return $this->redirect(['/user/auth/logout']);
    }

}

?>
