<?php

namespace App\Repositories;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ItemRepository
{
    public static function getItemsQuery()
    {
        return Item::all();
    }

    public static function addItemsQuery($request , $converter)
    {
        $itemData = array_merge($request->only('name', 'price', 'url'), ['description' => $converter->convert($request->get('description'))->getContent()] );
        return  Item::create($itemData);
    }

    public static function updateItemsQuery($request,$id,$converter)
    {
        $item = Item::findOrFail($id);
        $item->update([
                         'name' => $request->get('name'),
                         'url' => $request->get('url'),
                         'price' => $request->get('price'),
                         'description' => $converter->convert($request->get('description'))->getContent(),
                     ]);
        return $item;
    }

    public static function WishlistStatisticsQuery()
    {
        $totalItems = DB::table('items')->count();
        $averagePrice = DB::table('items')->avg('price');

        $websiteHighestTotal = DB::table('items')
            ->select(DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(url, '/', 3), '://', -1) as website"), DB::raw('SUM(price) as total_price'))
            ->groupBy('website')
            ->orderByDesc('total_price')
            ->first();

        $totalPriceThisMonth = DB::table('items')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('price');


        return [
            'total_items' => $totalItems,
            'average_price' => $averagePrice,
            'website_highest_total_price' => $websiteHighestTotal ? $websiteHighestTotal->website : null,
            'total_price_this_month' => $totalPriceThisMonth,
        ];

    }
}