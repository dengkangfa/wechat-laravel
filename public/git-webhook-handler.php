<?php
// git webhook 自动部署脚本
// 项目存放的路径
$path = '/var/www/laravel-wechat';
$requestBody = file_get_contents("php://input");
if (empty($requestBody)) {
    die('send fail');
}
$contents = json_decode($requestBody, true);
if ($contents['ref'] == 'refs/heads/master') {
    $res = shell_exec("cd {$path} && sudo git pull 2>&1");
    $res_log = '------------------------'.PHP_EOL;
    $res_log .= $contents['pusher']['name'] . ' 在' . date('Y-m-d H:i:s') . '向' . $contents['repository']['name'] . '项目的'
        . $contents['ref'] . '分支push了' . (isset($contents['total_commits_count']) ? $contents['total_commits_count'] : '1')  . '个commit:' . PHP_EOL;
    $res_log .= $res.PHP_EOL;
    $result = file_put_contents('git-webhook.txt', $res_log, FILE_APPEND);
    echo $result;
}
