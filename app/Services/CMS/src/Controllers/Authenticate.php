<?php

namespace Services\CMS\Controllers;

use PageController;
use SilverStripe\Security\Security;
use SilverStripe\Security\SecurityToken;

class Authenticate extends PageController
{
    public function index()
    {
        $session = $this->getRequest()->getSession();
        if (!Security::getCurrentUser()) {
            $backUrl = $this->getRequest()->getVar("BackURL");
            $session->set(self::class, $backUrl);
            return $this->redirect("/Security/login?BackURL=/authenticate");
        }
        $backUrl = $session->get(self::class);
        $query = parse_url($backUrl, PHP_URL_QUERY);
        $token = SecurityToken::getSecurityID();
        if ($query) {
            $backUrl .= "&token=$token";
        } else {
            $backUrl .= "?token=$token";
        }
        return $this->redirect($backUrl);
    }

    public function getLink()
    {
        return "/authenticate";
    }
}
