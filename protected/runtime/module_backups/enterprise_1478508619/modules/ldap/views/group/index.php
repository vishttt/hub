<?php

use yii\helpers\Url;
use yii\helpers\Html;
use humhub\widgets\GridView;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('EnterpriseModule.ldap', '<strong>LDAP</strong> member mapping'); ?>
    </div>
    <div class="panel-body">
        <?= \humhub\modules\admin\widgets\GroupMenu::widget(); ?>
        <p />        
        <p class="pull-right">
            <?php echo Html::a(Yii::t('EnterpriseModule.ldap', "Create new mapping"), Url::to(['edit']), array('class' => 'btn btn-primary')); ?>
        </p>
        <br>
        <?php
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-hover'],
            'columns' => [
                [
                    'label' => Yii::t('EnterpriseModule.ldap', 'Group'),
                    'format' => 'raw',
                    'value' => function($model) use ($groups) {
                        if (isset($groups[$model->group_id])) {
                            return $groups[$model->group_id];
                        }
                        return "Deleted group";
                    }
                ],
                'dn',
                [
                    'header' => 'Actions',
                    'class' => 'yii\grid\ActionColumn',
                    'options' => ['width' => '80px'],
                    'buttons' => [
                        'update' => function($url, $model) {
                            return Html::a('<i class="fa fa-pencil"></i>', Url::to(['edit', 'id' => $model->id]), ['class' => 'btn btn-primary btn-xs tt']);
                        },
                                'view' => function() {
                            return;
                        },
                                'delete' => function() {
                            return;
                        },
                            ],
                        ],
                ]]);
                ?>        
    </div>
</div>
