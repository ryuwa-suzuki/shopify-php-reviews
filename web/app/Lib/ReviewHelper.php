<?php
namespace App\Lib;
use App\Models\Review;
use Shopify\Auth\Session;
use Shopify\Rest\Admin2023_07\Metafield;

class ReviewHelper
{
    public function calculateAverageRating ($shopId, $productId)
    {
        $reviews = Review::where([
            'shop_id' => $shopId,
            'product_id' => $productId
        ])->get();

        $reviewCount = count($reviews);
        $sumRaiting = 0;

        foreach($reviews as $review) {
            $sumRaiting += $review->rating;
        }
        $avarageRating = $sumRaiting / $reviewCount;
        $roundAvarageRating = round($avarageRating);

        return ([$reviewCount, $roundAvarageRating]);
    }

    public function createMetafieldAvgRating (Session $authSession, $productId, $roundAvarageRating)
    {
        $Metafield = new Metafield($authSession);
        $Metafield->product_id = $productId;
        $Metafield->description = '商品評価平均';
        $Metafield->namespace = "review";
        $Metafield->key = "avg_rating";
        $Metafield->value = json_encode([
            "value" =>  $roundAvarageRating,
            "scale_min" => 1,
            "scale_max" => 5
        ]);
        $Metafield->type = "rating";
        $Metafield->save(true);
    }

    public function createMetafieldCount (Session $authSession, $productId, $reviewCount)
    {
        $Metafield = new Metafield($authSession);
        $Metafield->product_id = $productId;
        $Metafield->description = '商品評価数';
        $Metafield->namespace = "review";
        $Metafield->key = "count";
        $Metafield->value = $reviewCount;
        $Metafield->type = "number_integer";
        $Metafield->save(true);
    }
}
