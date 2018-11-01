<?php

namespace app\api\validate;

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
            throw new ParameterException([   // extends Exception  --> Exception Handle  --> Response
                'msg' => $this->error
            ]);
        }
        return Request::param();
    }

    protected function IsMustBePositiveInt($value)
    {
        if(is_numeric($value) && is_int($value + 0) && ($value + 0) > 0)
        {
            return true;
        }else
        {
            return false;
        }
    }
}
