<?php

namespace Services\Graduation\Extensions;

use OP\EBSWebservice;
use Services\CMS\Controllers\Authenticate;
use Services\EBS\Tasks\SyncMembers;
use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataExtension;

class Member extends DataExtension
{
    private static $db = [
        'QualificationJSON' => 'Text',
    ];

    public function canView()
    {
        if (Authenticate::getMemberIDByJWT() == $this->owner->ID) {
            return true;
        }
    }

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

    public function getQualifications()
    {
        $array = ArrayList::create();
        $json = json_decode($this->owner->QualificationJSON);
        if ($json) {
            foreach ($json->StaffQualifcation as $qualification) {
                $array->add(array_change_key_case((array)$qualification));
            }
        }
        return $array;
    }
}
