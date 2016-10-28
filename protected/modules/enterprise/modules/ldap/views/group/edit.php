<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<div class="panel panel-default">
    <?php if (!$model->isNewRecord) : ?>
        <div
            class="panel-heading"><?php echo Yii::t('EnterpriseModule.ldap', '<strong>Edit</strong> ldap mapping'); ?></div>
        <?php else: ?>
        <div
            class="panel-heading"><?php echo Yii::t('EnterpriseModule.ldap', '<strong>Create</strong> new ldap mapping'); ?></div>
        <?php endif; ?>
        <?= \humhub\modules\admin\widgets\GroupMenu::widget(); ?>
    <p />    

    <div class="panel-body">
        <?php $form = ActiveForm::begin([]) ?>

        <?= $form->field($model, 'group_id')->dropDownList($groups) ?>
        <?= $form->field($model, 'dn') ?>

        <?= Html::submitButton(Yii::t('base', 'Save'), ['class' => 'btn btn-primary']) ?>

        <?php if (!$model->isNewRecord): ?>
            <?= Html::a(Yii::t('base', 'Delete'), Url::to(['delete', 'id' => $model->id]), array('class' => 'btn btn-danger')); ?>
        <?php endif; ?>

        <?php ActiveForm::end() ?>

    </div>
</div>