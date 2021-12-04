<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Models\LogActivity;
use App\Models\User;
use App\Models\Monitor;


class DashboardController extends BaseController
{
    protected $logActivity = '';
    protected $user = '';
    protected $monitor = '';

    public function __construct(User $user, LogActivity $logActivity, Monitor $monitor)
    {
        $this->user = $user;
        $this->logActivity = $logActivity;
        $this->monitor = $monitor;
    }

    public function getData(){
        $today = date('Y-m-d');
        $usersCount = $this->user->count();
        $logsCount =  $this->logActivity->count();
        $urlsCount =  $this->monitor->count();
        $urlsOkCount =  $this->monitor->where('status_code', 200)->count();

        $lastLogs =  $this->logActivity->latest()->take(5)->get();
        $lastUrls =  $this->monitor->latest()->take(5)->get();

        $response = [
            'usersCount' => $usersCount,
            'logsCount' => $logsCount,
            'urlsCount' => $urlsCount,
            'urlsOkCount' => $urlsOkCount,
            'lastLogs' => $lastLogs,
            'lastUrls' => $lastUrls,
        ];
        return response()->json($response);

    }
}
