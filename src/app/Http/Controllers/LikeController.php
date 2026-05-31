<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store($item_id)
    {
        Like::firstOrCreate([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
        ]);

        return back();
    }

    public function destroy($item_id)
    {
        Like::where('user_id', Auth::id())
            ->where('item_id', $item_id)
            ->delete();

        return back();
    }
}
