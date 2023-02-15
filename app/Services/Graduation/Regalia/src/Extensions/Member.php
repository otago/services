<?php

namespace Services\Graduation\Extensions;

use OP\EBSWebservice;
use Services\EBS\Tasks\SyncMembers;
use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\DataExtension;

class Member extends DataExtension
{
    private static $db = [
        'QualificationJSON' => 'Text',
    ];

    public function onAfterSync()
    {
        $this->SyncQualificationsJSONWithEBS();
    }

    public function SyncQualificationsJSONWithEBS()
    {
        $ebs = EBSWebservice::connect();
        $result = $ebs->request(
            Config::inst()->get(
                SyncMembers::class, 'routes'
            )["getQualification"] . $this->owner->PayGlobalID
        );
        $this->owner->QualificationJSON = $result->Raw();
        $this->owner->write();
    }
}
