<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="container">
    <div class="row">
        <h1 class="section-title title"><a href="listing.html">Top News</a></h1>
       <?php
       foreach($spaces as $s){
       
       ?>
        <div class="col-md-3">
            <section class="blog-post">
                <div class="panel panel-default">
                     <?php echo \humhub\modules\space\widgets\Image::widget([
                                    'space' => $s,
                                   
                                    'htmlOptions' => [
                                        'class' => 'img-responsive',
                                    ]
                                ]); ?>
                    <div class="panel-body">
                        <div class="blog-post-meta">
                            <span class="label label-light label-info">Trends</span>
                            <p class="blog-post-date pull-right"> <?php echo \humhub\widgets\TimeAgo::widget(['timestamp' => $s->created_at]); ?></p>
                        </div>
                        <div class="blog-post-content">
                            <a href="<?php echo $s->getUrl(); ?>">
                                <h2 class="blog-post-title"><?php echo $s->name; ?></h2>
                            </a>
                            <p><?php echo $s->description; ?></p>
                            <a href="<?php echo $s->getUrl(); ?>" class="btn btn-info">Read more</a>
                            <?php echo \humhub\modules\space\widgets\FollowButton::widget(['space'=>$s]);?>
                            <div id="" class="pull-right">
                                <a onclick="
                                        var width = 575,
                                                height = 400,
                                                left = ($(window).width() - width) / 2,
                                                top = ($(window).height() - height) / 2,
                                                url = this.href;
                                        opts = 'status=1' +
                                                ',width=' + width +
                                                ',height=' + height +
                                                ',top=' + top +
                                                ',left=' + left;

                                        window.open(url, 'share', opts);

                                        return false;

                                   " href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Flamlt1.humhub.com%2Fcontent%2Fperma%3Fid%3D6&amp;description=We%27re+looking+for+great+slogans+of+famous+brands.+Maybe+you+can+come+up+with+some+samples%3F"><i style="font-size:16px;color:#3a5795" class="fa fa-facebook">&nbsp;</i></a>	    <a onclick="
                                           var width = 575,
                                                   height = 400,
                                                   left = ($(window).width() - width) / 2,
                                                   top = ($(window).height() - height) / 2,
                                                   url = this.href;
                                           opts = 'status=1' +
                                                   ',width=' + width +
                                                   ',height=' + height +
                                                   ',top=' + top +
                                                   ',left=' + left;

                                           window.open(url, 'share', opts);

                                           return false;

                                   " href="https://twitter.com/intent/tweet?text=We%27re+looking+for+great+slogans+of+famous+brands.+Maybe+you+can+come+up+with+some+samples%3F&amp;url=https%3A%2F%2Flamlt1.humhub.com%2Fcontent%2Fperma%3Fid%3D6"><i style="font-size:16px;color:#55acee" class="fa fa-twitter">&nbsp;</i>&nbsp;</a>	    <a onclick="
                                           var width = 575,
                                                   height = 400,
                                                   left = ($(window).width() - width) / 2,
                                                   top = ($(window).height() - height) / 2,
                                                   url = this.href;
                                           opts = 'status=1' +
                                                   ',width=' + width +
                                                   ',height=' + height +
                                                   ',top=' + top +
                                                   ',left=' + left;

                                           window.open(url, 'share', opts);

                                           return false;

                                   " href="https://www.linkedin.com/shareArticle?summary=&amp;mini=true&amp;source=&amp;title=We%27re+looking+for+great+slogans+of+famous+brands.+Maybe+you+can+come+up+with+some+samples%3F&amp;url=https%3A%2F%2Flamlt1.humhub.com%2Fcontent%2Fperma%3Fid%3D6&amp;ro=false"><i style="font-size:16px;color:#0177b5" class="fa fa-linkedin-square"></i></a>	</div>
                        </div>
                    </div>
                </div>
            </section><!-- /.blog-post -->
        </div>
       <?php } ?>
        <div class="col-md-3">
            <section class="blog-post">
                <div class="panel panel-default">
                    <?php if ($canCreateSpace){
                    echo Html::a(Yii::t('SpaceModule.widgets_views_spaceChooser', 'Create new space'), Url::to(['/space/create/create']), array('class' => 'btn btn-info col-md-12', 'data-target' => '#globalModal'));
                    }
                    ?>
                </div>
            </section>


        </div>
    </div>
</div>
