<?php

namespace App\Http\Controllers\API\V1;

use App\Models\LogActivity;
use Illuminate\Http\Request;

class MaintenanceController extends BaseController
{

    public function getActivityLog(){
        $logActivity = LogActivity::with('user')->latest()->paginate(10);
        return $this->sendResponse($logActivity, 'Log de atividades');
    }
}
