<?php

namespace Services\EBS\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\FieldType\DBDatetime;

class Member extends DataExtension
{
    private static $db = [
        'PayGlobalID'       => 'Int',
        'QualificationJSON' => 'Text',
        'Synced'            => DBDatetime::class,
    ];
}
