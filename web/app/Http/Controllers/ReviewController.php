<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Log;
use Shopify\Clients\Rest;
use App\Models\Session;
use App\Lib\ReviewHelper;

class ReviewController extends Controller
{
    private $reviewHelper;

    public function __construct(ReviewHelper $reviewHelper)
    {
        $this->reviewHelper = $reviewHelper;
    }

    public function index (Request $request)
    {
        $reviews = [];
        $session = $request->get('shopifySession');
        $shop = Session::where('shop', $session->getShop())->first();
        $reviews = Review::where('shop_id', $shop->id)->get();

        $productIds = [];
        foreach ($reviews as $review) {
            if (!in_array($review['product_id'], $productIds)) {
                $productIds[] = $review['product_id'];
            }
        }

        $client = new Rest($session->getShop(), $session->getAccessToken());
        $result = $client->get('products', [], ['ids'=> implode(',', $productIds)] );
        $products = $result->getDecodedBody();

        foreach ($reviews as $review) {
            foreach ($products['products'] as $product) {
                if ((int)$review['product_id'] === $product['id']) {
                    $review['product'] = $product;
                    break;
                }
            }
        }

        return response($reviews, 200);
    }

    public function create(Request $request)
    {
        $requestData = $request->all();
        $session = Session::where('shop', $requestData['shop'])->first();

        if(!$session) {
            abort(401);
        }

        $authSession = $session->instantiateAuthSession();

        $productId = $requestData['product_id'];

        try {
            Review::create([
                'name' => $requestData['name'],
                'shop_id' => $session->id,
                'product_id' => $requestData['product_id'],
                'rating' => $requestData['rating'],
                'comment' => $requestData['comment']
            ]);

            [
                $reviewCount,
                $roundAvarageRating
            ] = $this->reviewHelper->calculateAverageRating($session->id, $requestData['product_id']);

            $this->reviewHelper->createMetafieldAvgRating($authSession, $productId, $roundAvarageRating);
            $this->reviewHelper->createMetafieldCount($authSession, $productId, $reviewCount);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response($e->getMessage(), 500);
        }

        return response('', 201);
    }
}
