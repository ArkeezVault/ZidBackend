<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Serializers\ItemSerializer;
use App\Serializers\ItemsSerializer;
use App\Http\Requests\ItemRequest;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();

        return response()->json(['items' => (new ItemsSerializer($items))->getData()], 200);
    }

    public function store(ItemRequest $request, Item $item)
    {
        $createdItem = $item->saveItem(new Item, $request);

        return response()->json(['item' => $createdItem], 200);
    }

    public function show($id)
    {
        $item = Item::findOrFail($id);

        return response()->json(['item' => (new ItemSerializer($item))->getData()], 200);
    }

    public function update(ItemRequest $request, int $id, Item $item)
    {
        $updatedItem = $item->saveItem(Item::findOrFail($id), $request);

        return response()->json(['item' => $updatedItem], 200);
    }
}