<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<div class="modal-dialog">
    <div class="modal-content">
        <?php $form = ActiveForm::begin(['id' => 'create-news-form']); ?>

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"
                id="myModalLabel"><?php echo Yii::t("NewsModule.views_news_create", "Create News"); ?></h4>
        </div>
        <div class="modal-body">

            <?php echo $form->errorSummary($model); ?>

            <div class="form-group">
                        <?php echo $form->field($model, 'category')->dropdownList($categories); ?>

            </div>
            
            <div class="form-group">
                <?php echo $form->field($model, 'title'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->field($model, 'content', ['inputOptions' => ['class' => 'form-control', 'id' => 'contentText']])->textarea(); ?>
            </div>

        </div>
        <div class="modal-footer">
            <?php
            echo \humhub\widgets\AjaxButton::widget([
                'label' => Yii::t('NewsModule.views_news_create', 'Create'),
                'ajaxOptions' => [
                    'type' => 'POST',
                    'beforeSend' => '$.proxy(function() { $(this).prop("disabled",true); },this)',
                    'success' => 'function(html){ $("#globalModal").html(html); }',
                    'url' => Url::to(['/news/news/create']),
                ],
                'htmlOptions' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
            ?>

            <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('NewsModule.views_news_create', 'Close'); ?></button>

        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
