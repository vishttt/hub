<?php

/**
 * HumHub
 * Copyright © 2014 The HumHub Project
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 */

namespace humhub\modules\content\components\actions;

use Yii;
use humhub\modules\content\models\Content;

/**
 * ContentContainerStreamAction
 * Used to stream contents of a specific a content container.
 *
 * @since 0.11
 * @package humhub.modules_core.wall
 * @author luke
 */
class ContentContainerStream extends Stream {

    public $contentContainer;
    public $layout;

    public function init() {

        parent::init();

        // Get Content Container by Param
        if ($this->contentContainer->wall_id != "") {
            $this->activeQuery->andWhere("wall_entry.wall_id = " . $this->contentContainer->wall_id);
        } else {
            Yii::warning("No wall id for content container " . get_class($this->contentContainer) . " - " . $this->contentContainer->getPrimaryKey() . " set - stopped stream action!");
            // Block further execution
            $this->activeQuery->andWhere("1=2");
        }

        /**
         * Limit to public posts when no member
         */
        if (!$this->contentContainer->canAccessPrivateContent($this->user)) {
            if (!Yii::$app->user->isGuest) {
                $this->activeQuery->andWhere("content.visibility=" . Content::VISIBILITY_PUBLIC . " OR content.created_by = :userId", [':userId' => $this->user->id]);
            } else {
                $this->activeQuery->andWhere("content.visibility=" . Content::VISIBILITY_PUBLIC);
            }
        }

        /**
         * Handle sticked posts only in content containers
         */
        if ($this->limit != 1) {
            if ($this->from == '') {
                $oldOrder = $this->activeQuery->orderBy;
                $this->activeQuery->orderBy("");

                $this->activeQuery->addOrderBy('content.sticked DESC');
                $this->activeQuery->addOrderBy($oldOrder);
            } else {
                $this->activeQuery->andWhere("(content.sticked != 1 OR content.sticked is NULL)");
            }
        }
    }

    public function run() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $wallEntries = $this->getWallEntries();

        $output = "";
        $generatedWallEntryIds = [];
        $lastEntryId = "";
        $i = 0;
        foreach ($wallEntries as $wallEntry) {
            $i++;
            $underlyingObject = $wallEntry->content->getPolymorphicRelation();

            if ($underlyingObject === null) {
                throw new Exception('Could not get contents underlying object!');
            }

            $underlyingObject->populateRelation('content', $wallEntry->content);

            if ($this->layout == "timeline") {
                if ($i % 2 == 1) {
                    $output.="<li class=\"timeline-inverted\" > "
                            . "   <div class=\"timeline-badge\">"
                            . "<a><i class=\"fa fa-circle invert\" ></i></a>
                </div>";
                } else {
                    $output.="<li>"
                            . "   <div class=\"timeline-badge\">"
                            . "<a><i class=\"fa fa-circle invert\" ></i></a>
                </div>";
                }
            }
$output.=" <div class=\"timeline-panel\">";

            $output .= $this->controller->renderAjax('@humhub/modules/content/views/layouts/wallEntry', [
                'entry' => $wallEntry,
                'user' => $underlyingObject->content->user,
                'mode' => $this->mode,
                'object' => $underlyingObject,
                'content' => $underlyingObject->getWallOut()
                    ], true);
            $output.="</div>";
            if ($this->layout == "timeline")
                $output.="</li>";


            $generatedWallEntryIds[] = $wallEntry->id;
            $lastEntryId = $wallEntry->id;
        }
        
          $output .= '<li class="clearfix no-float"></li>';

        return [
            'output' => $output,
            'lastEntryId' => $lastEntryId,
            'counter' => count($wallEntries),
            'entryIds' => $generatedWallEntryIds
        ];
    }

}
