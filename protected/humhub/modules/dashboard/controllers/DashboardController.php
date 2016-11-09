<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\dashboard\controllers;

use Yii;
use humhub\components\Controller;
use humhub\models\Setting;
use humhub\modules\space\permissions\CreatePrivateSpace;
use humhub\modules\space\permissions\CreatePublicSpace;

class DashboardController extends Controller {

    public function init() {
        $this->appendPageTitle(\Yii::t('DashboardModule.base', 'Dashboard'));
        return parent::init();
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'acl' => [
                'class' => \humhub\components\behaviors\AccessControl::className(),
                'guestAllowedActions' => [
                    'index',
                    'stream'
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'stream' => [
                'class' => \humhub\modules\dashboard\components\actions\DashboardStream::className()
            ]
        ];
    }

    /**
     * Dashboard Index
     *
     * Show recent wall entries for this user
     */
    public function actionIndex() {
        if (Yii::$app->user->isGuest) {
            return $this->render('index_guest', array());
        } else {
            return $this->render('index', array(
                        'showProfilePostForm' => Yii::$app->getModule('dashboard')->settings->get('showProfilePostForm')
            ));
        }
    }

    public function actionHome() {

        $query = \humhub\modules\space\models\Space::find();
        $query->andWhere(['space.status' => 1]);

        $query->offset(0)->limit(10);



        return $this->render('home', array('spaces' => $query->all(), 'canCreateSpace' => $this->canCreateSpace()));
    }

    protected function canCreateSpace() {
        return (Yii::$app->user->permissionmanager->can(new CreatePublicSpace) || Yii::$app->user->permissionmanager->can(new CreatePrivateSpace()));
    }

}
