<?php

namespace App\Http\Controllers;

use App\Lib\AuthRedirection;
use App\Lib\EnsureBilling;
use App\Models\Session;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Shopify\Auth\OAuth;
use Shopify\Utils;
use Shopify\Webhooks\Registry;
use Shopify\Webhooks\Topics;

class AuthController extends Controller
{
    // use AuthorizesRequests;
    // use DispatchesJobs;
    // use ValidatesRequests;

    /**
     * ...
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function auth(Request $request): RedirectResponse
    {
        $shop = Utils::sanitizeShopDomain($request->query('shop'));

        // Delete any previously created OAuth sessions that were not completed(don't have an access token)
        Session::where('shop', $shop)->where('access_token', null)->delete();

        return AuthRedirection::redirect($request);
    }

    /**
     * ...
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function callback(Request $request): RedirectResponse
    {
        $session = OAuth::callback(
            $request->cookie(),
            $request->query(),
            ['App\Lib\CookieHandler', 'saveShopifyCookie'],
        );
        $shop = Utils::sanitizeShopDomain($request->query('shop'));

        /* resgister APP webhooks */
        $response = Registry::register(
            '/api/webhooks',
            Topics::APP_UNINSTALLED,
            $shop,
            $session->getAccessToken()
        );

        if ($response->isSuccess()) {
            Log::debug("Registered APP_UNINSTALLED webhook for shop $shop");

        } else {
            Log::error(
                "Failed to register APP_UNINSTALLED webhook for shop $shop with response body: " .
                    print_r($response->getBody(), true)
            );
        }

        $redirectUrl = Utils::getEmbeddedAppUrl($request->query('host'));

        if (Config::get('shopify.billing.required')) {
            list($hasPayment, $confirmationUrl) = EnsureBilling::check(
                $session,
                Config::get('shopify.billing')
            );

            if (!$hasPayment) {
                $redirectUrl = $confirmationUrl;
            }
        }

        return redirect($redirectUrl);
    }
}
