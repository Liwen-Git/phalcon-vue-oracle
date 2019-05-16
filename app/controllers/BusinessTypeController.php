<?php

namespace App\Controllers;

use App\Library\Result;
use App\Service\BusinessTypeService;

class BusinessTypeController extends ControllerBase
{
    public function businessTypesOfNewAction()
    {
        $type = new BusinessTypeService();
        $result = $type->getBusinessTypesOfNew();

        Result::success($result['data']);
    }
}
