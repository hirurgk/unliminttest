<?php

namespace Models;

use Kernel\{App,Model};

class User extends Model
{
    public function getPasswordHash($password)
    {
        return sha1(sha1($password));
    }
}
