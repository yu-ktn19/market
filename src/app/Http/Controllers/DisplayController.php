<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Support\Facades\Auth;

class DisplayController extends Controller
{
    public function create()
    {
        $categories = Category::all();

        return view('sell', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $path = $request->file('image')->store('item_images', 'public');

        $item = Item::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'brand_name' => $request->brand_name,
            'description' => $request->description,
            'price' => $request->price,
            'image_path' => 'storage/' . $path,
            'condition' => $request->condition,
            'is_sold' => false,
        ]);

        $item->categories()->attach($request->category_id);

        return redirect('/');
    }
}
