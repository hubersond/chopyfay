<?php

declare(strict_types=1);

namespace App\Lib;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Shopify\Auth\OAuth;
use Shopify\Context;
use Shopify\Utils;

class AuthRedirection
{
    public static function redirect(Request $request, bool $isOnline = false): RedirectResponse
    {
        $shop = Utils::sanitizeShopDomain($request->query("shop"));

        if (Context::$IS_EMBEDDED_APP && $request->query("embedded", false) === "1") {
            $redirectUrl = self::clientSideRedirectUrl($shop, $request->query());

        } else {
            $redirectUrl = self::serverSideRedirectUrl($shop, $isOnline);
        }

        return redirect($redirectUrl);
    }

    /**
     * Returns a URL that will be used for redirecting the user to the Shopify Authentication screen
     *
     * https://<my-shop>.myshopify.com/admin/oauth/authorize
     * ?client_id=a5f3e6681b1309607a41a306771d7001
     * &scope=write_products%2Cread_customers
     * &redirect_uri=http://localhost%3A50947/api/auth/callback
     * &state=8f8382e3-b21a-4e64-8987-5ea2dcef4cff
     * &grant_options%5B%5D=
     *
     * @param string $shop The shop name(The shop's Shopify domain name or hostname)
     * @param bool $isOnline
     *
     * @return string
     */
    private static function serverSideRedirectUrl(string $shop, bool $isOnline): string
    {
        return OAuth::begin(
            $shop,
            '/api/auth/callback',
            $isOnline,
            [ CookieHandler::class, 'saveShopifyCookie' ],
        );
    }

    private static function clientSideRedirectUrl($shop, array $query): string
    {
        $appHost = Context::$HOST_NAME;
        $redirectUri = urlencode("https://$appHost/api/auth?shop=$shop");
        $queryString = http_build_query(
            array_merge($query, ['redirectUri' => $redirectUri])
        );

        return "/ExitIframe?$queryString";
    }
}
