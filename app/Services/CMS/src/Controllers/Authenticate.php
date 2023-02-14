<?php

namespace Services\CMS\Controllers;

use Level51\JWTUtils\JWTUtils;
use PageController;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Injector\Injector;
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
}
