<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2016 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\news\components\actions;

use Yii;
use humhub\modules\content\models\Content;
use humhub\modules\content\models\WallEntry;
use humhub\modules\user\models\User;
use yii\base\ActionEvent;
use yii\base\Exception;

/**
 * DashboardStreamAction
 * Note: This stream action is also used for activity e-mail content.
 *
 * @since 0.11
 * @author luke
 */
class DashboardStream extends \yii\base\Action {

    /**
     * @event ActionEvent Event triggered before this action is run.
     * This can be used for example to customize [[activeQuery]] before it gets executed.
     * @since 1.1.1
     */
    const EVENT_BEFORE_RUN = 'beforeRun';

    /**
     * @event ActionEvent Event triggered after this action is run.
     * @since 1.1.1
     */
    const EVENT_AFTER_RUN = 'afterRun';

    /**
     * Constants used for sorting
     */
    const SORT_CREATED_AT = 'c';
    const SORT_UPDATED_AT = 'u';

    /**
     * Modes
     */
    const MODE_NORMAL = "normal";
    const MODE_ACTIVITY = "activity";

    /**
     * Maximum wall entries per request
     */
    const MAX_LIMIT = 50;

    /**
     * @var string
     */
    public $mode;

    /**
     * First wall entry id to deliver
     *
     * @var int
     */
    public $from;

    /**
     * Sorting Mode
     *
     * @var int
     */
    public $sort;

    /**
     * Maximum wall entries to return
     * @var int
     */
    public $limit = 4;

    /**
     * Filters
     *
     * @var array
     */
    public $filters = [];

    /**
     * @var \yii\db\ActiveQuery
     */
    public $activeQuery;

    /**
     * Optional stream user
     * if no user is specified, the current logged in user will be used.
     *
     * @var User
     */
    public $user;

    public function init() {

        $this->activeQuery = WallEntry::find();

        // If no user is set, take current if logged in
        if ($this->user === null && !Yii::$app->user->isGuest) {
            $this->user = Yii::$app->user->getIdentity();
        }


        // Read parameters
        if (!Yii::$app->request->isConsoleRequest) {
            $from = Yii::$app->getRequest()->get('from', 0);
            if ($from != 0) {
                $this->from = (int) $from;
            }


            $this->sort = static::SORT_CREATED_AT;


            $limit = Yii::$app->getRequest()->get('limit', '');
            if ($limit != "" && $limit <= self::MAX_LIMIT) {
                $this->limit = $limit;
            }

            $this->mode = self::MODE_NORMAL;
        }

        $this->activeQuery->joinWith('content');
        $this->activeQuery->joinWith('content.createdBy');
        $this->activeQuery->joinWith('content.contentContainer');

        $this->activeQuery->limit($this->limit);


      //  $this->activeQuery->andWhere(['content.object_model' => \humhub\modules\news\models\NewsPost::className()]);



     
            $this->activeQuery->orderBy('wall_entry.id DESC');
            if ($this->from != "")
                $this->activeQuery->andWhere("wall_entry.id < " . $this->from);
        

        $this->activeQuery->andWhere(['content.visibility' => \humhub\modules\content\models\Content::VISIBILITY_PUBLIC]);
    }

    public function run() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $wallEntries = $this->activeQuery->all();

        $output = "";
        $generatedWallEntryIds = [];
        $lastEntryId = "";
        foreach ($wallEntries as $wallEntry) {
           
            $underlyingObject = $wallEntry->content->getPolymorphicRelation();

            if ($underlyingObject === null) {
                throw new Exception('Could not get contents underlying object!');
            }

            $underlyingObject->populateRelation('content', $wallEntry->content);

            $output .= $this->controller->renderAjax('@humhub/modules/news/views/news/wallEntry', [

                'content' => $underlyingObject->getWallOut()
                    ], true);

            $generatedWallEntryIds[] = $wallEntry->id;
            $lastEntryId = $wallEntry->id;
        }

        return [
            'output' => $output,
            'lastEntryId' => $lastEntryId,
            'counter' => count($wallEntries),
            'entryIds' => $generatedWallEntryIds
        ];
    }

    /**
     * This method is called right before `run()` is executed.
     * You may override this method to do preparation work for the action run.
     * If the method returns false, it will cancel the action.
     *
     * @return boolean whether to run the action.
     */
    protected function beforeRun() {
        $event = new ActionEvent($this);
        $this->trigger(self::EVENT_BEFORE_RUN, $event);
        return $event->isValid;
    }

    /**
     * This method is called right after `run()` is executed.
     * You may override this method to do post-processing work for the action run.
     */
    protected function afterRun() {
        $event = new ActionEvent($this);
        $this->trigger(self::EVENT_AFTER_RUN, $event);
    }

}
