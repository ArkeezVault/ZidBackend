<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Http\Requests\ItemRequest;
use App\Serializers\ItemSerializer;
use League\CommonMark\CommonMarkConverter;

class Item extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function saveItem(Item $item, ItemRequest $request)
    {
        $converter = new CommonMarkConverter([
            'html_input' => 'escape', 
            'allow_unsafe_links' => false
        ]);

        $item->name = $request->get('name');
        $item->url = $request->get('url');
        $item->price = $request->get('price');
        $item->description = $converter->convert($request->get('description'))->getContent();
        $item->save();

        $serializer = new ItemSerializer($item);
        return $serializer->getData();
    }
}