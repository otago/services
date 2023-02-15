<?php

namespace Services\CMS\Controllers;

use Firebase\JWT\JWT;
use Level51\JWTUtils\JWTUtils;
use PageController;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\DataObject;
use SilverStripe\SAML\Authenticators\SAMLAuthenticator;
use SilverStripe\SAML\Authenticators\SAMLLoginHandler;
use SilverStripe\Security\Security;

class Authenticate extends PageController
{
    public function index()
    {
        $request = Injector::inst()->get(HTTPRequest::class);
        $absoluteBackUrl = $request->getSession()->get("AbsoluteBackURL");
        if (!$absoluteBackUrl) {
            $absoluteBackUrl = $this->getRequest()->getVar("AbsoluteBackURL");
            $request->getSession()->set("AbsoluteBackURL", $absoluteBackUrl);
        }
        if (!Security::getCurrentUser()) {
            $request->getSession()->set("BackURL", $this->getLink());
            $loginHandler = SAMLLoginHandler::create("/Security/login/default", new SAMLAuthenticator());
            return $loginHandler->doLogin([
                "BackURL" => $this->getLink()
            ], $loginHandler->loginForm(), $this->getRequest());
        }
        $payload = JWTUtils::inst()->byBasicAuth($this->getRequest());
        return $this->redirect($this->join_links($absoluteBackUrl, "?token=" . $payload['token']));
    }

    public function getLink()
    {
        return "/authenticate";
    }

    public static function getMemberIDByJWT()
    {
        if (Controller::has_curr()) {
            $token = Controller::curr()->getRequest()->getHeader("Authorization");
            if ($token && JWTUtils::inst()->check($token)) {
                return JWT::decode($token, Config::inst()->get(JWTUtils::class, 'secret'), ['HS256'])->memberId;
            }
        }
    }
}
