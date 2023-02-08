<?php

namespace Services\CMS\Resolvers;

use SilverStripe\Security\Security;

class Resolvers
{
    public static function resolveReadAuthenticatedMembers()
    {
        $results = [];

        if (Security::getCurrentUser()) {
            $results[] = [
                'id' => Security::getCurrentUser()->ID
            ];
        }

        return $results;
    }
}
