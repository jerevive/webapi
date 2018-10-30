<?php

namespace app\index\validate;

use think\Validate;

class Test extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'name' => ['require', 'min' => 3, 'max' => 5],
        'email' => ['require', 'email']
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'name.require' => '请填写姓名',
        'name.max' => '姓名长度不能超过5个字符',
        'name.min' => '姓名长度至少三个字符',
        'email.email' => '邮箱格式不正确'
    ];
}
