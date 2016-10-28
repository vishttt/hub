<?php

use humhub\modules\user\models\User;
use humhub\widgets\TopMenu;
use humhub\widgets\NotificationArea;
use humhub\modules\user\widgets\ProfileHeaderControls;

return [
    'id' => 'news',
    'class' => 'humhub\modules\news\Module',
    'namespace' => 'humhub\modules\news',
    'events' => [
        ['class' => User::className(), 'event' => User::EVENT_BEFORE_DELETE, 'callback' => ['humhub\modules\news\Events', 'onUserDelete']],
        ['class' => TopMenu::className(), 'event' => TopMenu::EVENT_INIT, 'callback' => ['humhub\modules\news\Events', 'onTopMenuInit']],
    ],
];
?>