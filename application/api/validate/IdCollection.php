<?php

namespace app\api\validate;


class IdCollection extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'ids' => ['require', 'checkIds']
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'ids' => 'id必须是以英文逗号分隔的正整数'
    ];

    protected function checkIds($value)
    {
        $ids = explode(',', $value);

        foreach($ids as $id)
        {
            return $this->IsMustBePositiveInt($id);
        }
    }
}
