<?php

namespace App\Services;

use App\Models\Item;
use App\Repositories\ItemRepository;
use App\Serializers\ItemSerializer;
use Illuminate\Support\Facades\DB;
use League\CommonMark\CommonMarkConverter;
use Carbon\Carbon;

class ItemService
{
    public function __construct(){}

    public function getItems()
    {
        $items = ItemRepository::getItemsQuery();
        return $items;
    }

    public function addItem($request)
    {
        $converter = new CommonMarkConverter(['html_input' => 'escape', 'allow_unsafe_links' => false]);
        $item = new ItemSerializer(ItemRepository::addItemsQuery($request , $converter));
        return $item;
    }

    public function getItem($id)
    {
        $item = Item::findOrFail($id);
        $item = new ItemSerializer($item);
        return $item;
    }

    public function upddateItem($request,$id)
    {
        $converter = new CommonMarkConverter(['html_input' => 'escape', 'allow_unsafe_links' => false]);
        $item = ItemRepository::updateItemsQuery($request ,$id,$converter);
        return $item;
    }

    public function calculateWishlistStatistics()
    {
        $stats = ItemRepository::WishlistStatisticsQuery();
        return $stats;
    }
}