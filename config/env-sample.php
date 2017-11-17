<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2017/11/16
 * Time: 17:03
 */

# Determine the `node` command path.
$config['node_executable_path'] = 'node';
$config['log'] = [
    'path' => __DIR__ . '/../log',
];
$config['task_store'] = [
    'path' => __DIR__ . '/../tasks',
];
$config['output'] = [
    'dir' => __DIR__ . '/../output',
    'mail_sender' => [
        'host' => '',
        'smtp_auth' => '',
        'username' => '',
        'password' => '',
        'smtp_secure' => '',
        'port' => '',
        'display_name' => '',
    ],
];