<?php

namespace app\api\validate;

use app\api\exception\ParameterException;
use think\facade\Request;
use think\Validate;

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
        return true;
    }

    protected function IsMustBePositiveInt($value)
    {
        $value = (int) $value;
        if(is_numeric($value) && is_int($value + 0) && ($value + 0) > 0)
        {
            return true;
        }else
        {
            return false;
        }
    }

    protected function isNotEmpty($value)
    {
        if(empty($value))
        {
            return false;
        }
        return true;
    }

    public function getDataByRule($data)
    {
        if(array_key_exists('user_id', $data) || array_key_exists('uid', $data))
        {
            throw new ParameterException(['code' => '403', 'msg' => '请求参数中含有非法的uid或user_id']);
        }

        $newArray = [];
        foreach ($this->rule as $key => $value)
        {
            $newArray[$key] = $data[$key];
        }
        return $newArray;
    }
}
