<?php

namespace app\api\validate;

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
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'id' => 'id必须是正整数'
    ];

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
