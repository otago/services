<?php

namespace Services\CMS\Models;

use Firebase\JWT\JWT;
use Level51\JWTUtils\JWTUtils;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\DataObject as ORMDataObject;
use SilverStripe\Security\Member;

class DataObject extends ORMDataObject
{
    private static $table_name = 'Services_CMS_Models_DataObject';

    public static function getMemberViaAuthorizationHeaderJWT($member)
    {
        if (!$member) {
            $token = Controller::curr()->getRequest()->getHeader("Authorization");
            if ($token && JWTUtils::inst()->check($token)) {
                $memberId = JWT::decode($token, Config::inst()->get(JWTUtils::class, 'secret'), ['HS256'])->memberId;
                $member = ORMDataObject::get_by_id(Member::class, $memberId);
            }
        }
        return $member;
    }

    public function canCreate($member = null, $context = [])
    {
        $member = self::getMemberViaAuthorizationHeaderJWT($member);
        return parent::canCreate($member, $context);
    }

    public function canView($member = null)
    {
        $member = self::getMemberViaAuthorizationHeaderJWT($member);
        return parent::canView($member);
    }

    public function canEdit($member = null)
    {
        $member = self::getMemberViaAuthorizationHeaderJWT($member);
        return parent::canEdit($member);
    }

    public function canDelete($member = null)
    {
        $member = self::getMemberViaAuthorizationHeaderJWT($member);
        return parent::canDelete($member);
    }
}
