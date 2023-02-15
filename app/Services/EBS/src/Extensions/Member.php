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

    public function Sync($data)
    {
        foreach ($data->toMap() as $key => $value) {
            if ($key == "ID") {
                continue;
            }
            $this->owner->setField($key, $value);
        }
        $this->owner->Synced = DBDatetime::now()->getValue();
        $this->owner->write();
        $this->owner->extend('onAfterSync', $data);
    }
}
