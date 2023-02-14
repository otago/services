<?php

namespace Services\EBS\Tasks;

use OP\EBSWebservice;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\Security\Member;

class SyncMembers extends BuildTask
{
    public function run($request)
    {
        echo "Connecting to EBS...\n";
        $ebs = EBSWebservice::connect();
        echo "Requesting all staff data...\n";
        $result = $ebs->request($this->config()->routes["getAllStaff"]);
        foreach ($result->Data()->staff as $staffData) {
            $member = Member::get()->filter("Email", $staffData->PrimaryEmail)->First();
            if (!$member) {
                continue;
            }
            $this->SyncMember($member, $staffData);
        }
    }

    public function SyncMember($member, $staffData)
    {
        echo "Syncing member $member->Title...\n";
        foreach ($staffData->toMap() as $key => $value) {
            if ($key == "ID") {
                continue;
            }
            $member->setField($key, $value);
        }
        $member->Synced = DBDatetime::now()->getValue();
        $member->write();
    }
}
