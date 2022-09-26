<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;
use Shopify\Context;
use Shopify\Utils;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;


    /*
    <<<DEV>>
    */
    public function sandbox()
    {
        $data = [
            'app_name' => 'Chop Y Fay',
            'app_env' => App::environment(),
            'shopify' => config('shopify.app_url'),
            'foo' => base64_decode('Y2hvcC15LWZheS5teXNob3BpZnkuY29tL2FkbWlu'),
        ];

        return response()->json($data);
    }
    /*
    <<<DEV>>
    */


    public function index(Request $request)
    {
        if (Context::$IS_EMBEDDED_APP &&  $request->query("embedded", false) === "1") {
            if (env('APP_ENV') === 'production') {
                return file_get_contents(public_path('index.html'));

            } else {
                return file_get_contents(base_path('frontend/index.html'));
            }

        } else {
            return redirect(Utils::getEmbeddedAppUrl($request->query("host", null)) . "/" . $request->path());
        }
    }
}
