<?php

namespace App\Helpers;
use Request;
use App\Models\LogActivity as LogActivityModel;


class LogActivity
{


    public static function addToLog($type, $subject )
    {
        $log = [];
        $log['type'] = $type;
        $log['subject'] = $subject;
        $log['url'] = Request::fullUrl();
        $log['method'] = Request::method();
        $log['ip'] = Request::ip();
        $log['agent'] = Request::header('user-agent');
        $log['user_id'] = auth()->check() ? auth()->user()->id : 1;
        $save = LogActivityModel::create($log);

        return $save->id;
    }


    public static function logActivityLists()
    {
        return LogActivityModel::latest()->get();
    }


}
