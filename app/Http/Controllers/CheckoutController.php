<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use function foo\func;
use Illuminate\Http\Request;
use App\Classes\ShoppingCart;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\DB;

class CheckoutController extends BaseController
{
    public function __construct()
    {
        $this->page = 'checkout';
        parent::__construct();
    }

    public function index()
    {
        $cartItems = ShoppingCart::getCartItems();
        return view('checkout.index', compact('cartItems'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $email = $request->input('email');
        $cartItems = ShoppingCart::getCartItems();
        if (\Auth::check() || $exists_user = User::exists($email)) {
            $user = (\Auth::check()) ? \Auth::user() : $exists_user;
            $user_profile = $user->profile;
            if ($user_profile->last_name == null) {
                $user_profile->last_name = $request->input('last_name');
                $user_profile->save();
            }
            if ($user_profile->first_name == null) {
                $user_profile->first_name = $request->input('first_name');
                $user_profile->save();
            }
            if ($user_profile->patronymic == null) {
                $user_profile->patronymic = $request->input('patronymic');
                $user_profile->save();
            }
            if ($user_profile->phone == null) {
                $user_profile->phone = $request->input('phone');
                $user_profile->save();
            }
            if ($user_profile->address == null) {
                $user_profile->address = $request->input('address');
                $user_profile->save();
            }

            $order_details = $request->only(['phone', 'address', 'order_notes']);
            $order_details['user_id'] = $user->id;

            DB::transaction(function () use ($order_details, $cartItems) {
                $order_id = Order::create($order_details)->id;

                foreach($cartItems as $cartItem) {
                    $product = $cartItem->product;
                    OrderProduct::create([
                        'order_id' => $order_id, 'pvp_id' => $product->vendors[0]->pivot->id, 'product_id' => $cartItem->product_id,
                        'quantity' => $cartItem->quantity, 'price' => $cartItem->product->getPrice()
                    ]);
                }

                ShoppingCart::clearCart();
            });

            return back()->with('message', 'Заказ успешно принят. В ближающее время вам позвонить оператор.');
        } else {
            $credentials = $request->only('email');
            $password = substr(md5(now()), 3, 8);
            $credentials['password'] = bcrypt($password);
            DB::transaction(function () use ($credentials, $request, $cartItems){
                $user_id = User::create($credentials)->id;
                $data = $request->all();
                $data['user_id'] = $user_id;
                UserProfile::create($data);
                $order_details = $request->only(['phone', 'address', 'order_notes']);
                $order_details['user_id'] = $user_id;
                $order_id = Order::create($order_details)->id;

                foreach($cartItems as $cartItem) {
                    $product = $cartItem->product;
                    OrderProduct::create([
                        'order_id' => $order_id, 'pvp_id' => $product->vendors[0]->pivot->id, 'product_id' => $cartItem->product_id,
                        'quantity' => $cartItem->quantity, 'price' => $cartItem->product->getPrice()
                    ]);
                }

                ShoppingCart::clearCart();
            });
            return back()->with('message', 'Заказ успешно принят. В ближающее время вам позвонить оператор.');
        }
    }
}
