<?php

namespace Services\Graduation\Regalia\Admins;

use Services\Graduation\Regalia\Models\Ceremony;
use Services\Graduation\Regalia\Models\Submission;
use SilverStripe\Admin\ModelAdmin;

class Admin extends ModelAdmin
{
    private static $menu_title = 'Regalia';
    private static $url_segment = 'regalia';

    private static $menu_icon_class = 'font-icon-list';

    private static $managed_models = [
        Submission::class,
        Ceremony::class
    ];
}
