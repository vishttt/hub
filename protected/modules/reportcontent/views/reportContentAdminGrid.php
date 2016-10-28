<?php

use humhub\modules\reportcontent\models\ReportContent;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php if (count($reportedContent) == 0): ?>
    <br/> <br/>
    <?php echo Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'There are no reported posts.') ?>
    <br/> <br/>
<?php endif; ?>

<?php if (count($reportedContent) > 0): ?>

    <table class="table table-hover">
        <thead>
            <tr>
                <th></th>
                <th><?php echo Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Content'); ?></th>
                <th><?php echo Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Reason'); ?></th>
                <th></th>
                <th><?php echo Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Reporter'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reportedContent as $report) : ?>
                <tr>
                    <td width="52px">
                        <a href="<?php echo $report->getSource()->content->user->getUrl(); ?>">

                            <img class="media-object img-rounded"
                                 src="<?php echo $report->getSource()->content->user->profileImage->getUrl(); ?>" width="48"
                                 height="48" alt="48x48" data-src="holder.js/48x48"
                                 style="width: 48px; height: 48px;">
                        </a>
                    </td>
                    <td>
                        <div class="content" style="max-height: 40px; max-width:250px;">              

                            <p id="content-message-<?php echo $report->id ?>" style="display: inline;" class="contentAnchor"><?= \humhub\widgets\RichText::widget(['text' => $report->getSource()->getContentDescription(), 'minimal' => true, 'maxLength' => 60]) ?></p>
                            <br/>    
                            <small class="media">
                                <span class="time"><?php echo Yii::t('ReportcontentModule.base', 'created by :displayName', array(':displayName' => Html::a(Html::encode($report->getSource()->content->user->displayName), $report->getSource()->content->user->getUrl()))) ?></span>
                                <?php echo \humhub\widgets\TimeAgo::widget(['timestamp' => $report->content->created_at]); ?>
                            </small>     
                        </div>
                    </td>
                    <td style="font-weight:bold; vertical-align:middle">
                        <?php echo ReportContent::getReason($report->reason); ?>
                    </td>
                    <td width="52px">
                        <a href="<?php echo $report->user->getUrl(); ?>">

                            <img class="media-object img-rounded"
                                 src="<?php echo $report->user->profileImage->getUrl(); ?>" width="48"
                                 height="48" alt="48x48" data-src="holder.js/48x48"
                                 style="width: 48px; height: 48px;">
                        </a>
                    </td>
                    <td>
                        <div class="content">
                            <strong><?php echo Yii::t('ReportcontentModule.base', 'by :displayName', array(':displayName' => Html::a(Html::encode($report->user->displayName), $report->user->getUrl()))) ?></strong>
                            <br/>
                            <small class="media" title="<?php echo $report->created_at; ?>"><?php echo \humhub\widgets\TimeAgo::widget(['timestamp' => $report->created_at]); ?></small>
                        </div>
                    </td>
                    <td style="vertical-align:middle">
                        <?php
                        echo \humhub\widgets\ModalConfirm::widget(array(
                            'uniqueID' => 'delete_' . $report->id,
                            'title' => Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', '<strong>Confirm</strong> report deletion'),
                            'message' => Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Do you really want to approve this post?'),
                            'buttonTrue' => Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Approve'),
                            'buttonFalse' => Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Cancel'),
                            'cssClass' =>  'btn btn-success btn-sm',
                            'linkContent' => Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Approve post'),
                            'linkHref' => Url::to(["//reportcontent/report-content/appropriate", 'id' => $report->id, 'admin' => $isAdmin]),
                        ));
                        ?>
                        <?php
                        echo \humhub\widgets\ModalConfirm::widget(array(
                            'uniqueID' => $report->id,
                            'title' => Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', '<strong>Confirm</strong> post deletion'),
                            'message' => Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Do you really want to delete this post? All likes and comments will be lost!'),
                            'buttonTrue' => Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Delete'),
                            'buttonFalse' => Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Cancel'),
                            'cssClass' => 'btn btn-sm btn-danger',
                            'linkContent' => Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Delete post'),
                            'linkHref' => Url::to(["//reportcontent/report-content/delete-content", 'admin' => $isAdmin, 'model' => get_class($report->getSource()), 'id' => $report->getSource()->id]),
                        ));
                        ?>
                        <a href="<?= $report->content->getUrl() ?>" class="btn btn-sm btn-primary"><i aria-hidden="true" class="fa fa-eye"></i></a>
                    </td>
                <?php endforeach; ?>
        </tbody>
    </table>

    <div class="pagination-container">
        <?= \humhub\widgets\LinkPager::widget(['pagination' => $pagination]); ?>
    </div>

<?php endif; ?>

<script type="text/javascript">

    $(document).ready(function () {
        $('.contentAnchor').each(function () {
            var divh = $(this).parent().height();
            var divw = $(this).parent().width();

            while ($(this).outerHeight() > divh || $(this).outerWidth() > divw) {
                $(this).text(function (index, text) {
                    return text.replace(/\W*\s(\S)*$/, '...');
                });
            }
        });
    });
</script>