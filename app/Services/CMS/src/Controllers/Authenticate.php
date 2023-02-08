<?php

namespace Services\CMS\Controllers;

use PageController;
use SilverStripe\Security\Security;

class Authenticate extends PageController
{
    public function index()
    {
        // $referer = $_SERVER["HTTP_REFERER"];
        // echo '<Pre>'; var_dump($referer); die();
        if (!Security::getCurrentUser()) {
            return $this->redirect("/Security/login?BackURL=/authenticate");
        }
    }
}
