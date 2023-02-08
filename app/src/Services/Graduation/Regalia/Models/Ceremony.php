<?php

namespace Services\Graduation\Regalia\Models;

use SilverStripe\ORM\DataObject;

class Ceremony extends DataObject
{
    private static $table_name = 'Services_Regalia_Models_Ceremony';

    private static $belongs_many_many = [
        "Submissions" => Submission::class
    ];
}
