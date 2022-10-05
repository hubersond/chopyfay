<?php

namespace App\Http\Controllers;

use App\Exceptions\ShopifyProductCreatorException;
use App\Lib\ProductCreator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Shopify\Auth\Session as AuthSession;
use Shopify\Clients\Rest as Client;
use Shopify\Rest\Admin2022_07\Product;

class ProductController extends Controller
{
    /**
     * Adds a new product to store
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        /** @var AuthSession */
        $session = $request->get('shopifySession'); // Provided by the shopify.auth middleware, guaranteed to be active
        $success = $code = $error = null;

        try {
            ProductCreator::call($session, 5);
            $success = true;
            $code = 200;
            $error = null;

        } catch (\Exception $e) {
            $success = false;

            if ($e instanceof ShopifyProductCreatorException) {
                $code = $e->response->getStatusCode();
                $error = $e->response->getDecodedBody();

                if (array_key_exists("errors", $error)) {
                    $error = $error["errors"];
                }

            } else {
                $code = 500;
                $error = $e->getMessage();
            }

            Log::error("Failed to create products: $error");

        } finally {
            return response()->json(["success" => $success, "error" => $error], $code);
        }
    }

    public function createRest(Request $request)
    {
        /** @var AuthSession */
        $session = $request->get('shopifySession');
        $product = new Product($session);

        $product->title = $request->json('title');
        $product->body_html = $request->json('description');
        $product->images = [
            [ 'src' => $request->json('image'), ],
        ];

        $product->save(true);

        return response()->json($product->toArray());
    }

    /**
     * Counts shop products
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function count(Request $request): JsonResponse
    {
        // Provided by the shopify.auth middleware, guaranteed to be active
        /** @var AuthSession */
        $session = $request->get('shopifySession');

        $client = new Client($session->getShop(), $session->getAccessToken());
        $response = $client->get('products/count')->getDecodedBody();

        return response()->json($response);
        // return response()->json(['type' => gettype($response), 'count' => 8 ]);
    }

    /**
     * Returns all shop products
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        // Provided by the shopify.auth middleware, guaranteed to be active
        /** @var AuthSession */
        $session = $request->get('shopifySession');

        $client = new Client($session->getShop(), $session->getAccessToken());
        $response = $client->get('products')->getDecodedBody();

        return response()->json($response);
    }
}
