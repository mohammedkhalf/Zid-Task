<?php

namespace App\Repositories;
use App\Models\Item;
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
}