<?php

use \humhub\widgets\LayoutAddons;

return [
    'id' => 'termsbox',
    'class' => 'humhub\modules\termsbox\Module',
    'namespace' => 'humhub\modules\termsbox',
    'events' => [
        ['class' => LayoutAddons::className(), 'event' => LayoutAddons::EVENT_INIT, 'callback' => ['humhub\modules\termsbox\Module', 'onLayoutAddonsInit']],
    ],
];
?>