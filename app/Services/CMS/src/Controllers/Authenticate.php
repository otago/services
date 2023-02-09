<?php

namespace Services\CMS\Controllers;

use Level51\JWTUtils\JWTUtils;
use PageController;
use SilverStripe\Security\Security;

class Authenticate extends PageController
{
    private static $allowed_actions = [
        'jwt'
    ];

    public function index()
    {
        // $session = $this->getRequest()->getSession();
        // if (!Security::getCurrentUser()) {
        //     $backUrl = $this->getRequest()->getVar("BackURL");
        //     $session->set(self::class, $backUrl);
        //     return $this->redirect("/Security/login?BackURL=/authenticate");
        // }
        // $backUrl = $session->get(self::class);
        // $payload = JWTUtils::inst()->byBasicAuth($this->getRequest());
        // return json_encode($payload);
        // return $this->redirect($backUrl);
    }

    public function jwt()
    {
        $payload = JWTUtils::inst()->byBasicAuth($this->getRequest());
        return json_encode($payload);
    }

    public function getLink()
    {
        return "/authenticate";
    }
}
