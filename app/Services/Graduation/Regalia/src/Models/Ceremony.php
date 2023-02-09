<?php

namespace Services\Graduation\Regalia\Models;

use Services\CMS\Traits\JWT;
use SilverStripe\ORM\DataObject;

class Ceremony extends DataObject
{
    use JWT;

    private static $table_name = 'Services_Regalia_Models_Ceremony';

    private static $db = [
        'Title' => 'Varchar(255)',
    ];

    private static $belongs_many_many = [
        "Submissions" => Submission::class
    ];

    private static $summary_fields = [
        'ID' => 'ID',
        'Title' => 'Title',
        'Submissions.Count' => 'Submissions',
        'Created' => 'Created',
        'LastEdited' => 'Last Edited',
    ];
}
