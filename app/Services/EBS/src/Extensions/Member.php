<?php

namespace Services\EBS\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\FieldType\DBDatetime;

class Member extends DataExtension
{
    private static $db = [
        'PayGlobalID'       => 'Int',
        'Qualifications'    => 'Text',
        'Synced'            => DBDatetime::class,
    ];
}
