<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Category_item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
   public function index(Request $request)
   {
      $tab = $request->query('tab', 'recommend');
    $keyword = $request->query('keyword');

    $query = Item::query();

    if ($keyword) {
        $query->where('name', 'like', "%{$keyword}%");
    }

    if ($tab === 'mylist') {
        if (!Auth::check()) {
            $items = collect();
        } else {
            $query->whereHas('likes', function ($q) {
                $q->where('user_id', Auth::id());
            });
            $items = $query->latest()->get();
        }
    } else {
        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }
        $items = $query->latest()->get();
    }

    return view('index', compact('items', 'tab', 'keyword'));
   }
   

   public function show($item_id)
   {
        $item = Item::with([
            'user',
            'categories',
            'comments.user',
            'likes',
        ])->findOrFail($item_id);

        $isLiked = false;

        if (Auth::check()) {
            $isLiked = $item->likes()
                ->where('user_id', Auth::id())
                ->exists();
        }

        return view('detail', compact('item', 'isLiked'));
      
   }
}
