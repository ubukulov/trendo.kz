<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;

class IndexController extends BaseController
{
    public function welcome()
    {
        $recommended_products = Product::whereNotNull('images')->inRandomOrder()->take(6)->get();
        $on_sales = Product::whereNotNull('images')->inRandomOrder()->take(6)->get();
        $most_populars = Product::whereNotNull('images')->inRandomOrder()->take(6)->get();
        $special_offer = Product::whereNotNull('images')->inRandomOrder()->first();
        return view('welcome', compact('recommended_products', 'on_sales', 'most_populars', 'special_offer'));
    }

    public function lead(Request $request)
    {
        $product_id = $request->input('product_id');
        $first_name = $request->input('first_name');
        $phone_number = $request->input('phone_number');
        //$comments = (empty($request->input('comments'))) ? "" : $request->input('comments');
        $product = Product::find($product_id);
        $to = "optpricealmaty@gmail.com" . ", ";
        $to .= "kairat_ubukulov@mail.ru";
        $subject = "Новая заявка";
        $title = $product->title;
        $url = $product->url();
        $message = "
        <html>
        <head>
         <title>Новая заявка</title>
        </head>
        <body>
        <p>Имя: $first_name</p>
        <p>Телефон: $phone_number</p>
        <p>Товар: $title</p>
        <p>Ссылка: <a href=\"$url\" target='_blank'>$title</a></p>
        </body>
        </html>
        ";

        /* Для отправки HTML-почты вы можете установить шапку Content-type. */
        $headers= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";

        /* дополнительные шапки */
        $headers .= "From: Новая заявка <likemoneyworld@gmail.com>\r\n";
//        $headers .= "Cc: birthdayarchive@example.com\r\n";
        mail($to, $subject, $message, $headers);
        return redirect()->back();
    }
}
