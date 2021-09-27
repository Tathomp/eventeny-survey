<?php

namespace App\Models;

class CouponCode 
{
    public ?int $id = null;
    public ?string $code = null;
    public ?string $description = null;

    public function loadData($data)
    {
        $this->code = $data['code'];
        $this->description = $data['message'];
    }

    public function save()
    {

    }
}