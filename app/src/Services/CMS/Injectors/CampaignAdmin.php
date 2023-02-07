<?php

namespace Services\CMS\Injectors;

use SilverStripe\CampaignAdmin\CampaignAdmin as CampaignAdminCampaignAdmin;

class CampaignAdmin extends CampaignAdminCampaignAdmin
{
    public function canView($member = null)
    {
        return false;
    }
}
