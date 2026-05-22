<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CheckoutBuild;
use App\Models\CheckoutOrders;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class OrderController extends Controller
{
    public function index(Request $request)
{
    $checkouts = CheckoutBuild::where('user_id', auth()->user()->id)->get();
    $orders = collect(); // Inicia como Eloquent Collection

    foreach ($checkouts as $checkout) {
        $ord = CheckoutOrders::where('checkout_id', $checkout->id)->get();
        $orders = $orders->concat($ord); // concat preserva keys e mantém tipo Collection
    }

    return view('profile.orders', compact('orders','checkouts'));
}
}
