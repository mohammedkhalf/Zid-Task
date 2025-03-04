<?php

namespace App\Http\Controllers;

use App\Serializers\ItemSerializer;
use App\Serializers\ItemsSerializer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\ItemService;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;

class ItemController extends Controller
{
    protected ItemService $itemService;
    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function index()
    {
        $items = $this->itemService->getItems();
        return new JsonResponse(['items' => (new ItemsSerializer($items))->getData()]);
    }

    public function store(StoreItemRequest $request)
    {
        $item = $this->itemService->addItem($request);
        return new JsonResponse(['item' => $item->getData()]);
    }

    public function show($id)
    {
        $item = $this->itemService->getItem($id);
        return new JsonResponse(['item' => $item->getData()]);
    }

    public function update(UpdateItemRequest $request, int $id): JsonResponse
    {
        $item = $this->itemService->upddateItem($request,$id);
        return new JsonResponse(['item' => (new ItemSerializer($item))->getData()]);
    }
}
