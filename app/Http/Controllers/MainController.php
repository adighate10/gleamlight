<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class MainController extends Controller
{
    //
    public function receive(Request $request)
    {
        $data = $request->all();
        //get the user’s id
        $id = $data["entry"][0]["messaging"][0]["sender"]["id"];

        if($data["text"]!=null and $data["message"]["text"]=="Hi") {
            //$this->sendTextMessage($id, "hi, $id \nWelcome to Gleamlight: A Smart Home Automation Project Developed By Ajay, Neelam, Puja and Manu.");
            $this->sendTextMessage($id, $data);
            $this->saveApiData();
        }
        /*$this->sendTextMessage($id, $data);
        $this->saveApiData();*/

    }

    private function sendTextMessage($recipientId, $messageText)
    {
        $messageData = [
            "recipient" => [
                "id" => $recipientId
            ],
            "message" => [
                "text" => $messageText
            ]
        ];
        $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
        curl_exec($ch);

    }
    public function saveApiData()
    {
        $client = new Client();
        $res = $client->request('GET', 'https://us-central1-glee-bc8ce.cloudfunctions.net/addMessage');
        echo $res->getStatusCode();
        // "200"
        echo $res->getHeader('content-type');
        // 'application/json; charset=utf8'
        echo $res->getBody();
    }
}
