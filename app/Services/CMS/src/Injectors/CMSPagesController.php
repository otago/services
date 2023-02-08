<?php

namespace Services\CMS\Injectors;

use SilverStripe\CMS\Controllers\CMSPagesController as ControllersCMSPagesController;

class CMSPagesController extends ControllersCMSPagesController
{
    public function canView($member = null)
    {
        return false;
    }
}
