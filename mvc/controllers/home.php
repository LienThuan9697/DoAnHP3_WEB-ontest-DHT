<?php

class Home extends Controller
{
    public function default()
    {
        $this->view("landing", [
            "Title" => "DHT OnTest - Hệ thống thi trực tuyến",
            "Script" => "landing",
            "Plugin" => [
                "jq-appear" => 1,
                "slick" => 1
            ]
        ]);
    }

}
