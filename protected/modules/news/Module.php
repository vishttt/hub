<?php

namespace humhub\modules\news;

/**
 * MailModule provides messaging functions inside the application.
 *
 * @package humhub.modules.news
 * @since 0.5
 */
class Module extends \humhub\components\Module
{

    /**
     * @inheritdoc
     */
    public function getPermissions($contentContainer = null)
    {
        if ($contentContainer !== null && $contentContainer instanceof \humhub\modules\user\models\User) {
            return [
                new permissions\SendMail()
            ];
        }

        return [];
    }

}
