<?php

namespace Services\Graduation\Regalia\Models;

use Services\CMS\Traits\JWT;
use SilverStripe\Security\Member;
use SilverStripe\ORM\DataObject;

class Submission extends DataObject
{
    use JWT;

    private static $table_name = 'Services_Regalia_Models_Submission';

    private static $db = [
        'TrencherSize'          => 'Int',
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
        $member = $member ?: $this->getMemberViaAuthorizationHeaderJWT();
        return parent::canView($member) || $member && $member->ID == $this->MemberID;
    }
}
