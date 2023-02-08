<?php

namespace Services\Graduation\Regalia\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;

class Submission extends DataObject
{
    private static $table_name = 'Services_Regalia_Models_Submission';

    private static $has_one = [
        'Member' => Member::class
    ];

    private static $many_many = [
        "Ceremonies" => Ceremony::class
    ];
}
