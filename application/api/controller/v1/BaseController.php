<?php
/**
 * Created by Jesse.
 * Date: 2018/11/2
 * Time: 10:47
 */

namespace app\api\controller\v1;


use think\Controller;
use app\api\facade\Token;

class BaseController extends Controller
{
    protected function checkPrimaryScope()
    {
        Token::needPrimaryScope();
    }

    protected function checkExclusiveScope()
    {
        Token::needExclusiveScope();
    }
}