<?php

namespace App\Console\Commands;

use App\Vendor;
use Illuminate\Console\Command;
use App\Product;
use Illuminate\Support\Facades\DB;
use App\PVP;

class Alser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alser:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		date_default_timezone_set("Asia/Almaty");
        $this->info("Attempting to connect email ...");
//        ini_set('memory_limit', '4096M');
        /* connect to gmail */
        $hostname = "{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX";
        $username = 'optpricealmaty@gmail.com';
        $password = 'Optprice2019@#';

        /* try to connect */
        $inbox = imap_open($hostname, $username, $password, OP_READONLY) or die('Cannot connect to Gmail: ' . imap_last_error());

        /* grab emails */
        $emails = imap_search($inbox, 'FROM "likemoneyworld@gmail.com"');

        /* if any emails found, iterate through each email */
        if ($emails) {

            /* put the newest emails on top */
            rsort($emails);

            $email_number = $emails[0];

            /* get mail structure */
            $structure = imap_fetchstructure($inbox, $email_number);

            $attachments = array();

            /* if any attachments found... */
            if (isset($structure->parts) && count($structure->parts)) {
                for ($i = 0; $i < count($structure->parts); $i++) {
                    $attachments[$i] = array(
                        'is_attachment' => false,
                        'filename' => '',
                        'name' => '',
                        'attachment' => ''
                    );

                    if ($structure->parts[$i]->ifdparameters) {
                        foreach ($structure->parts[$i]->dparameters as $object) {
                            if (strtolower($object->attribute) == 'filename') {
                                $attachments[$i]['is_attachment'] = true;
                                $attachments[$i]['filename'] = $object->value;
                            }
                        }
                    }

                    if ($structure->parts[$i]->ifparameters) {
                        foreach ($structure->parts[$i]->parameters as $object) {
                            if (strtolower($object->attribute) == 'name') {
                                $attachments[$i]['is_attachment'] = true;
                                $attachments[$i]['name'] = $object->value;
                            }
                        }
                    }

                    if ($attachments[$i]['is_attachment']) {
                        $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i + 1);

                        /* 4 = QUOTED-PRINTABLE encoding */
                        if ($structure->parts[$i]->encoding == 3) {
                            $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                        } /* 3 = BASE64 encoding */
                        elseif ($structure->parts[$i]->encoding == 4) {
                            $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                        }
                    }
                }
            }

            /* iterate through each attachment and save it */
            foreach ($attachments as $attachment) {
                if ($attachment['is_attachment'] == 1) {
                    /* prefix the email number to the filename in case two emails
                     * have the attachment with the same file name.
                     */
                    $file_name = base_path() . "/public/stocks/alser.xls";
                    $fp = fopen($file_name, "w+");
                    fwrite($fp, $attachment['attachment']);
                    fclose($fp);
                }

            }

            /* close the connection */
            imap_close($inbox);
        }

        $this->info("Alser file has been download.");
        $this->info("Process updating ...");

        require_once public_path() . "/Classes/PHPExcel/IOFactory.php";
        require_once public_path() . "/Classes/PHPExcel/Cell.php";

        $file_name = public_path() . "/stocks/alser.xls";

        $objPHPExcel = \PHPExcel_IOFactory::load($file_name);

        $count = 0;

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            for ($row = 9; $row <= $highestRow; ++ $row) {
                $article = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                $title = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $base_price = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $quantity = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                if (is_numeric($article)) {
                    $count++;
                    DB::transaction(function () use ($title, $article, $base_price, $quantity) {
                        $pvpItem = PVP::where(['article' => $article])->first();
                        if ($pvpItem) {
                            $pvpItem->update([
                                'quantity' => $quantity, 'base_price' => $base_price, 'updated_at' => date('Y-m-d H:i:s')
                            ]);
                        } else {
                            $lastInsertId = Product::create([
                                'title' => $title,
                            ])->id;

                            PVP::create([
                                'product_id' => $lastInsertId, 'vendor_id' => 1, 'quantity' => $quantity,
                                'price' => 0, 'base_price' => $base_price, 'product_title' => $title,
                                'article' => $article
                            ]);
                        }
                    });
                }
            }
        }

        $vendor = Vendor::find(1);
        if ($vendor) {
            $vendor->quantity = $count;
            $vendor->save();
        } else {
            Vendor::create([
                'title' => 'ТОО "Gulser Computers"', 'alias' => 'alser', 'type' => 1, 'quantity' => $count,
                'active' => 1
            ]);
        }

        $this->info('Process updating completed');
    }
}
