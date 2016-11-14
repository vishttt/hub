<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->registerCssFile('@web/resources/achieve/demo.css');
$this->registerCssFile('@web/resources/achieve/dp_calendar.css');

$this->registerJsFile('@web/resources/achieve/jquery.ui.core.js');
$this->registerJsFile('@web/resources/achieve/jquery.ui.position.js');
$this->registerJsFile('@web/resources/achieve/jquery.ui.datepicker.js');
$this->registerJsFile('@web/resources/achieve/date.js');
$this->registerJsFile('@web/resources/achieve/dp_calendar.js');
$this->registerJsVar('activityStreamUrl', $streamUrl);
?>

<div class="container">
    <div class="row">

        <div class="dp_calendar" id="calendar"></div>
        <div id="activityStream">
            <div id="activityEmpty" style="display:none">
                <div
                    class="placeholder"><?php echo Yii::t('ActivityModule.widgets_views_activityStream', 'There are no activities yet.'); ?></div>
            </div>
            <ul id="activityContents" class="media-list activities">
                <li id="activityLoader">
                    <?php echo \humhub\widgets\LoaderWidget::widget(); ?>
                </li>
            </ul>

        </div>

    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var cur_date=new Date();
        $("#calendar").dp_calendar({
            onChangeDay: function (date_selected) {

                if (cur_date !== date_selected) {
                console.log(cur_date);

                    $('#activityContents').html('');
                    loadMoreActivities();
                }
                cur_date = date_selected;


                console.log(cur_date);
            }}
        );


        var activityLastLoadedEntryId = "";

        // save if the last entries are already loaded
        var activityLastEntryReached = false;

        // listen for scrolling event yes or no
        var scrolling = true;

        // hide loader
        $("#activityLoader").hide();

        $('#achieveContents').scroll(function () {

            // save height of the overflow container
            var _containerHeight = $("#achieveContents").height();

            // save scroll height
            var _scrollHeight = $("#achieveContents").prop("scrollHeight");

            // save current scrollbar position
            var _currentScrollPosition = $('#achieveContents').scrollTop();

            // load more activites if current scroll position is near scroll height
            if (_currentScrollPosition >= (_scrollHeight - _containerHeight - 30)) {

                // checking if ajax loading is necessary or the last entries are already loaded
                if (activityLastEntryReached == false) {

                    if (scrolling == true) {

                        // stop listening for scrolling event to load the new activity range only one time
                        scrolling = false;

                        // load more activities
                        loadMoreActivities();
                    }
                }

            }

        });

        /**
         * load new activities
         */
        function loadMoreActivities() {



            // save url for activity reloads
            var _url = activityStreamUrl.replace('-from-', activityLastLoadedEntryId);

            // show loader
            $("#activityLoader").show();

            // load json
            jQuery.getJSON(_url, function (json) {

                if (activityLastLoadedEntryId == "" && json.counter == 0) {

                    // show placeholder if no results exists
                    $("#activityEmpty").show();

                    // hide loader
                    $("#activityLoader").hide();

                } else {

                    // add new activities
                    $("#activityLoader").before(json.output);

                    // save the last activity id for the next reload
                    activityLastLoadedEntryId = json.lastEntryId;

                    if (json.counter < 10) {
                        // prevent the next ajax calls, if there are no more entries
                        activityLastEntryReached = true;

                        // hide loader
                        $("#activityLoader").hide();
                    }

                }

                // start listening for the scrolling event
                scrolling = true;

            });
        }

        // load the first activities
        loadMoreActivities();

    });


</script>