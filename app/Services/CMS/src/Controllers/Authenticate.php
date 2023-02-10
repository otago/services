<?php

namespace Services\CMS\Controllers;

use Level51\JWTUtils\JWTUtils;
use PageController;
use SilverStripe\Security\Security;

class Authenticate extends PageController
{
    public function index()
    {
        $session = $this->getRequest()->getSession();
        $backUrl = $session->get(self::class);
        if (!$backUrl) {
            $backUrl = $this->getRequest()->getVar("BackURL");
            $session->set(self::class, $backUrl);
        }
        if (!Security::getCurrentUser()) {
            return $this->redirect("/Security/login?BackURL=/authenticate");
        }
        $session->clear(self::class);
        $payload = JWTUtils::inst()->byBasicAuth($this->getRequest());
        return $this->redirect($this->join_links($backUrl, "?token=" . $payload['token']));
    }

    public function getLink()
    {
        return "/authenticate";
    }
}
