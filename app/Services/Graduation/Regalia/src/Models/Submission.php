<?php

namespace Services\Graduation\Regalia\Models;

use Services\CMS\Controllers\Authenticate;
use SilverStripe\Security\Member;
use SilverStripe\ORM\DataObject;

class Submission extends DataObject
{
    private static $table_name = 'Services_Regalia_Models_Submission';

    private static $db = [
        'TrencherSize'          => 'Int',
        'RegaliaInfo'           => 'Text',
        'DeliveryLocation'      => 'Text',
        'Comments'              => 'Text',
        'Pickup'                => 'Boolean',
        'GownRequired'          => 'Boolean',
        'TrencherRequired'      => 'Boolean',
        'GownLength'            => 'Varchar(255)',
        'PreferredGown'         => 'Varchar(255)',
        'GownRequirementsOther' => 'Varchar(255)',
        'GownInstitutionOther'  => 'Varchar(255)',
    ];

    private static $has_one = [
        'Member' => Member::class
    ];

    private static $many_many = [
        "Ceremonies" => Ceremony::class
    ];

    private static $summary_fields = [
        'ID' => 'ID',
        'Member.ID' => 'Member #ID',
        'Ceremonies.Count' => 'Ceremonies',
        'Created' => 'Created',
        'LastEdited' => 'Last Edited',
    ];

    public function canView($member = null)
    {
        $member = $member ?? DataObject::get_by_id(Member::class, Authenticate::getMemberIDByJWT());
        return parent::canView($member) || $member && $member->ID == $this->MemberID;
    }

    public function canEdit($member = null)
    {
        return $this->canView($member);
    }

    public function canCreate($member = null, $context = [])
    {
        $member = $member ?? DataObject::get_by_id(Member::class, Authenticate::getMemberIDByJWT());
        if ($member && !self::get()->filter("MemberID", $member->ID)->First()) {
            return true;
        }
        return parent::canCreate($member, $context);
    }

    public function onBeforeWrite()
    {
        if ($this->isChanged("ceremonyIdsJSON")) {
            $this->Ceremonies()->removeAll();
            $this->Ceremonies()->setByIDList(json_decode($this->getChangedFields()["ceremonyIdsJSON"]["after"]));
        }
        $member = $member ?? DataObject::get_by_id(Member::class, Authenticate::getMemberIDByJWT());
        if (!$this->MemberID) {
            $this->MemberID = $member->ID;
        }
        parent::onBeforeWrite();
    }

    public function getCeremonyIdsJSON()
    {
        return json_encode(array_keys($this->Ceremonies()->map()->toArray()));
    }
}
