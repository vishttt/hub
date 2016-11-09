<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\enterprise\modules\ldap\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use humhub\modules\admin\components\Controller;

/**
 * Description of GroupController
 *
 * @author luke
 */
class GroupController extends Controller
{

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => \humhub\modules\enterprise\modules\ldap\models\Group::find(),
            'pagination' => ['pageSize' => 50],
        ]);


        $groups = \yii\helpers\ArrayHelper::map(\humhub\modules\user\models\Group::find()->all(), 'id', 'name');
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'groups' => $groups
        ]);
    }

    public function actionEdit()
    {
        $id = Yii::$app->request->get('id');

        $model = null;
        if ($id != '') {
            $model = \humhub\modules\enterprise\modules\ldap\models\Group::findOne(['id' => $id]);
        }

        if ($model === null) {
            $model = new \humhub\modules\enterprise\modules\ldap\models\Group;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            $this->redirect(Url::to(['index']));
        }

        $groups = \yii\helpers\ArrayHelper::map(\humhub\modules\user\models\Group::find()->all(), 'id', 'name');
        return $this->render('edit', ['model' => $model, 'groups' => $groups]);
    }

    public function actionDelete()
    {
        $id = Yii::$app->request->get('id');

        $model = \humhub\modules\enterprise\modules\ldap\models\Group::findOne(['id' => $id]);

        if ($model !== null) {
            $model->delete();
        }

        $this->redirect(['index']);
    }

}
