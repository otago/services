<?php

namespace Services\EBS\Tasks;

use OP\EBSWebservice;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\Security\Member;

class SyncMembers extends BuildTask
{
    public $ebs;

    public function run($request)
    {
        echo "Connecting to EBS...\n";
        $this->ebs = EBSWebservice::connect();

        echo "Requesting all staff data...\n";
        $result = $this->ebs->request($this->config()->routes["getStaff"] . "0");

        foreach ($result->Data()->staff as $staffData) {
            $member = Member::get()->filter("Email", $staffData->PrimaryEmail)->First();
            if (!$member) {
                continue;
            }
            $member = $this->SyncMember($member, $staffData);
            $this->SyncQualification($member);
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
        return $member;
    }

    public function SyncQualification($member)
    {
        echo "Syncing qualification for $member->Title...\n";
        $result = $this->ebs->request($this->config()->routes["getQualification"] . $member->PayGlobalID);
        $member->QualificationJSON = $result->Raw();
        $member->write();
    }
}
