<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Serializers\ItemSerializer;
use App\Serializers\ItemsSerializer;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    private $validationArray = [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'url' => 'required|url',
        'description' => 'required|string',
    ];

    public function index()
    {
        $items = Item::all();

        return response()->json(['items' => (new ItemsSerializer($items))->getData()], 200);
    }

    public function store(Request $request, Item $item)
    {
        $this->validate($request, $this->validationArray);

        $createdItem = $item->saveItem(new Item, $request);

        return response()->json(['item' => $createdItem]);
    }

    public function show($id)
    {
        $item = Item::findOrFail($id);

        $serializer = new ItemSerializer($item);

        return response()->json(['item' => $serializer->getData()]);
    }

    public function update(Request $request, int $id, Item $item)
    {
        $this->validate($request, $this->validationArray);

        $updatedItem = $item->saveItem(Item::findOrFail($id), $request);

        return response()->json(['item' => $updatedItem]);
    }
}