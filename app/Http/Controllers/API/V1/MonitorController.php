<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\LogActivity;
use App\Models\Monitor;
use Illuminate\Http\Request;
use App\Http\Requests\Monitor\MonitorRequest;
use Illuminate\Support\Facades\Http;
use App\Util\HttpStatusCode;

class MonitorController extends BaseController
{
    use HttpStatusCode;

    /**
     * Create a new controller instance.
     *
     * @param monitor $monitor
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $urls = Monitor::latest()->paginate(10);

        return $this->sendResponse($urls, 'Url lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MonitorRequest $request)
    {
        $url = Monitor::create([
            'url' => $request->get('url'),
            'description' => $request->get('description'),
        ]);

        LogActivity::addToLog('info','Nova URL ' . $request['url'] . ' foi adicionada.' );

        return $this->sendResponse($url, 'URL Cadastrada com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\monitor  $monitor
     * @return \Illuminate\Http\Response
     */
    public function show(monitor $monitor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\monitor  $monitor
     * @return \Illuminate\Http\Response
     */
    public function edit(monitor $monitor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\monitor  $monitor
     * @return \Illuminate\Http\Response
     */
    public function update(MonitorRequest $request, $id)
    {
        $url = Monitor::findOrFail($id);

        $url->update($request->all());

        LogActivity::addToLog('info','URL ' . $url['url'] . ' foi editada.' );

        return $this->sendResponse($url, 'URL foi atualizada');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\monitor  $monitor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $this->authorize('isAdmin');

        $url = Monitor::findOrFail($id);

        $url->delete();

        if($url->delete()){
            LogActivity::addToLog('warning','URL ' . $url->url . ' foi deletada.' );
        }

        return $this->sendResponse( $url, 'URL foi apagada');
    }


    public function trackingMonitor(){
        $urls = Monitor::all()->toArray();

        foreach ($urls as $keyUrl => $urlValue){
            try{
                $response = Http::get($urlValue["url"]);
                    $url = Monitor::find($urlValue["id"]);
                    $url->response = base64_encode($response->body());
                    $url->status_code = $response->status();
                    $url->status_code_description = $this->statusCodeDescription($response->status());
                    $url->timestamp = strtotime(date("Y-m-d H:i:s"));
                    $url->save();
            }catch (\Exception $e){
                $url = Monitor::find($urlValue["id"]);
                $url->response = base64_encode("Could not resolve host");
                $url->status_code = 0;
                $url->status_code_description = "Could not resolve host";
                $url->timestamp = strtotime(date("Y-m-d H:i:s"));
                $url->save();
            }

        }

        return true;
    }


}
