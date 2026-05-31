<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Address;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function create(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        $postal_code = $request->query('postal_code', $user->postal_code);
        $address = $request->query('address', $user->address);
        $building = $request->query('building', $user->building);
        $payment_method = $request->query('payment_method', '');

        return view('purchase', compact(
            'item',
            'user',
            'postal_code',
            'address',
            'building',
            'payment_method'
        ));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        $address = Address::create([
            'user_id' => $user->id,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);


        Purchase::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'address_id' => $address->id,
            'payment_method' => $request->payment_method,
            'stripe_session_id' => null,
            'purchased_at' => now(),
        ]);

        $item->update([
            'is_sold' => true,
        ]);

        return redirect('/');
    }

    public function editAddress(Request $request, $item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);
        $payment_method = $request->query('payment_method', '');

        return view('address', compact('user', 'item', 'payment_method'));
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        return redirect('/purchase/' . $item_id . '?' . http_build_query([
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
            'payment_method' => $request->payment_method,
        ]));
    }
}