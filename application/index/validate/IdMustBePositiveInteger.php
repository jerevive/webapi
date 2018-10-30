<?php

namespace app\index\validate;

class IdMustBePositiveInteger extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'id' => ['require', 'IsMustBePositiveInt'],
        'num' => ['in:1,2,3']
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];

    public function IsMustBePositiveInt($value, $rule, $data=[], $filed, $desc)
    {
        if(is_numeric($value) && is_int($value + 0) && ($value + 0) > 0)
        {
            return true;
        }else
        {
            return $filed.'格式不正确';
        }
    }
}
