<?php

namespace app\index\validate;

use app\exception\ParameterException;
use think\Exception;
use think\Validate;
use think\facade\Request;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        if(!$this->batch()->check(Request::param()))
        {
            throw new ParameterException([
                'msg' => $this->error
            ]);
        }
        return true;
    }
}
