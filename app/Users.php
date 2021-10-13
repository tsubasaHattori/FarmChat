<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    public function getData()
    {
        return '名前:'.$this->name.'---メール:'.$this->email;
    }
}
