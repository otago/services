<?php

namespace Services\CMS\Controllers;

use Level51\JWTUtils\JWTUtils;
use PageController;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Injector\Injector;
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
            return $this->redirect("/Security/login");
        }
        $payload = JWTUtils::inst()->byBasicAuth($this->getRequest());
        return $this->redirect($this->join_links($absoluteBackUrl, "?token=" . $payload['token']));
    }

    public function getLink()
    {
        return "/authenticate";
    }
}
