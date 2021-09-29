<?php

namespace App\Models;

use App\Classes\Database;

class CouponCode
{
    public ?int $id = null;
    public string $code;
    public string $message;

    public function __construct()
    {
        $this->code = " ";
        $this->message = " ";
    }

    public function loadData($data)
    {
        $this->setCode($data['code']);
        $this->setMessage($data['message']);
    }

    public function save()
    {
        $db = Database::$db;
        return $db->createCode($this);
    }

    public function update($surveyId)
    {
        $db = Database::$db;
        $db->updateCouponCode($this, $surveyId);
    }


    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code)
    {
        $this->code = $code;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;
    }

}