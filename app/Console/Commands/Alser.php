<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        /* connect to gmail */
        $hostname = "{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX";
        $username = 'optpricealmaty@gmail.com';
        $password = 'Optprice2019@#';

        /* try to connect */
        $inbox = imap_open($hostname,$username,$password, OP_READONLY) or die('Cannot connect to Gmail: ' . imap_last_error());

        /* grab emails */
        $emails = imap_search($inbox,'UNSEEN');

        /* if emails are returned, cycle through each... */
        if($emails) {

            /* begin output var */
            $output = '';

            /* put the newest emails on top */
            rsort($emails);

            /* for every email... */
            foreach($emails as $email_number) {

                /* get information specific to this email */
                $overview = imap_fetch_overview($inbox,$email_number,0);
                $message = imap_fetchbody($inbox,$email_number,2);

                /* output the email header information */
                $output.= '<div class="toggler '.($overview[0]->seen ? 'read' : 'unread').'">';
                $output.= '<span class="subject">'.$overview[0]->subject.'</span> ';
                $output.= '<span class="from">'.$overview[0]->from.'</span>';
                $output.= '<span class="date">on '.$overview[0]->date.'</span>';
                $output.= '</div>';

                /* output the email body */
                $output.= '<div class="body">'.$message.'</div>';
            }

            echo $output;
        }

        /* close the connection */
        imap_close($inbox);
    }
}
