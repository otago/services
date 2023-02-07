<?php

namespace Services\CMS\Injectors;
use SilverStripe\VersionedAdmin\ArchiveAdmin as VersionedAdminArchiveAdmin;

class ArchiveAdmin extends VersionedAdminArchiveAdmin
{
    public function canView($member = null)
    {
        return false;
    }
}
