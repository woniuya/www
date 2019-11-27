<?php
namespace app\stock\command;
use think\console\Command;
use think\Config;
use think\console\Input;
use think\console\Output;
use think\Log;
class Crontab extends Command
{  
    protected function configure()  
    {  
        $this->setName('Crontab')->setDescription('Crontab');
    }  
  
    protected function execute(Input $input, Output $output)
    {
        $this->doCron();
        $output->writeln("The timing task is finished");
    }
    public function doCron()
    {  
        // 记录开始运行的时间  
        $GLOBALS['_beginTime'] = microtime(TRUE);  
        /* 永不超时 */
        ini_set('max_execution_time', 0);  
        $time   = time();  
        $exe_method = [];  
        $crond_list = Config::get('crond');
        $sys_crond_timer = Config::get('sys_crond_timer');
        foreach ( $sys_crond_timer as $format ){
            $key = date($format, ceil($time));
            if (isset($crond_list[$key])&&is_array(@$crond_list[$key])){
                $exe_method = array_merge($exe_method, $crond_list[$key]);
            }
        }
        if (!empty($exe_method))
        {
            foreach ($exe_method as $method)
            {
                if(!is_callable($method))
                {  
                    //方法不存在的话就跳过不执行  
                    continue;  
                }  
                log::write("Run crond --- {$method}()");
                $runtime_start = microtime(true);  
                call_user_func($method);
                $runtime = microtime(true) - $runtime_start;
                log::write("{$method}(), execution time: {$runtime}");
            }  
            $time_total = microtime(true) - $GLOBALS['_beginTime'];
            log::write("total:{$time_total}");
        }  
    }
}