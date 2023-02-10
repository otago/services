<?php

namespace Services\CMS\Traits;

use Firebase\JWT\JWT as JWTJWT;
use Level51\JWTUtils\JWTUtils;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;

trait JWT {
    public function getMemberViaAuthorizationHeaderJWT()
    {
        $token = Controller::curr()->getRequest()->getHeader("Authorization");
        if ($token && JWTUtils::inst()->check($token)) {
            $memberId = JWTJWT::decode($token, Config::inst()->get(JWTUtils::class, 'secret'), ['HS256'])->memberId;
            return DataObject::get_by_id(Member::class, $memberId);
        }
    }
}
