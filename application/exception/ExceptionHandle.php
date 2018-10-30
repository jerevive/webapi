<?php
/**
 * Created by Jesse.
 * Date: 2018/10/30
 * Time: 13:55
 */

namespace app\exception;


use Exception;
use think\exception\Handle;
use think\facade\Config;
use think\facade\Log;
use think\facade\Request;
use think\facade\Env;

class ExceptionHandle extends Handle
{
    private $errorCode;
    private $code;
    private $msg;

    public function render(Exception $e)
    {
        if($e instanceof BaseException)
        {
            $this->code = $e->code;
            $this->errorCode = $e->errorCode;
            $this->msg = $e->msg;
        }else {
            if (Config::get('app_debug'))
            {
                return parent::render($e);
            }else
            {
                $this->addErrorLog($e);
                $this->code = 500;
                $this->errorCode = 999;
                $this->msg = '服务器内部错误';
            }

        }
        return json([
            'errorCode' => $this->errorCode,
            'msg'  => $this->msg,
            'url'  => Request::url(true)
        ], $this->code);
    }

    private function addErrorLog(Exception $e)
    {
        Log::init([
            // 日志记录方式，内置 file socket 支持扩展
            'type'        => 'File',
            // 日志保存目录
            'path'        => Env::get('config_path').'/../logs/',
            // 日志记录级别
            'level'       => ['error'],
            // 单文件日志写入
            'single'      => false,
            // 独立日志级别
            'apart_level' => [],
            // 最大日志文件数量
            'max_files'   => 30,
            // 是否关闭日志写入
            'close'       => false,
        ]);

        Log::write($e->getMessage(), 'error');
    }
}