<?php

namespace Services\CMS\Injectors;

use SilverStripe\Reports\ReportAdmin as ReportsReportAdmin;

class ReportAdmin extends ReportsReportAdmin
{
    public function canView($member = null)
    {
        return false;
    }
}
