<?php

namespace app\index\validate;

use think\Exception;
use think\Validate;
use think\facade\Request;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        if(!$this->check(Request::param()))
        {
            throw new Exception($this->error);
        }
        return true;
    }
}
