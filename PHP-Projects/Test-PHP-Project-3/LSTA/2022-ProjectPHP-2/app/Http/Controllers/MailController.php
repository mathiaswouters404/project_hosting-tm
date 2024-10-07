<?php

namespace App\Http\Controllers;

use App\Helpers\Json;
use App\Mail\MailFactory;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\Object_;
use Ramsey\Uuid\Type\Integer;

class MailController extends Controller
{
    public function sendMail(){
        $mail = new MailFactory("hallo dit is een test mail","hofmanswarre@gmail.com","hofmanswarre@gmail.com","warre", "halllo test");
        $mail->sendMail();
}

    public function PostMail(Request $request){
        return $request;
    }



}
