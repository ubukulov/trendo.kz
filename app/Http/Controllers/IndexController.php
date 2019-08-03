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

    public function query(Request $request)
    {
        $q = $request->input('q');
        $category_id = $request->input('category_id');
        $max_page = 20;
        $results = $this->search($q, $max_page);
        return view('search', compact('results', 'q'));
    }

    /**
     * Полнотекстовый поиск.
     *
     * @param string $q Строка содержащая поисковый запрос. Может быть несколько фраз разделенных пробелом.
     * @param integer $count Количество найденных результатов выводимых на одной странице (для пагинации)
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function search($q, $count){
        $query = mb_strtolower($q, 'UTF-8');
        $arr = explode(" ", $query); //разбивает строку на массив по разделителю
        /*
         * Для каждого элемента массива (или только для одного) добавляет в конце звездочку,
         * что позволяет включить в поиск слова с любым окончанием.
         * Длинные фразы, функция mb_substr() обрезает на 1-3 символа.
         */
        $query = [];
        foreach ($arr as $word)
        {
            $len = mb_strlen($word, 'UTF-8');
            switch (true)
            {
                case ($len <= 3):
                    {
                        $query[] = $word . "*";
                        break;
                    }
                case ($len > 3 && $len <= 6):
                    {
                        $query[] = mb_substr($word, 0, -1, 'UTF-8') . "*";
                        break;
                    }
                case ($len > 6 && $len <= 9):
                    {
                        $query[] = mb_substr($word, 0, -2, 'UTF-8') . "*";
                        break;
                    }
                case ($len > 9):
                    {
                        $query[] = mb_substr($word, 0, -3, 'UTF-8') . "*";
                        break;
                    }
                default:
                    {
                        break;
                    }
            }
        }
        $query = array_unique($query, SORT_STRING);
        $qQeury = implode(" ", $query); //объединяет массив в строку
        // Таблица для поиска
        $results = Product::whereRaw(
            "MATCH(title) AGAINST(? IN BOOLEAN MODE)", // name,email - поля, по которым нужно искать
            $qQeury)->paginate($count) ;
        return $results;
    }
}
