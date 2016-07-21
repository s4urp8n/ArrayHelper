<?php

$codeceptionStatus = null;
$config = include 'tests' . DIRECTORY_SEPARATOR . 'config.php';

//Build commands array
$commands = [
    [
        'description' => 'Package testing started...',
    ],
    [
        'callback'    => function ()
        {
            //Turn on implicit flush
            ob_implicit_flush(true);

            //Change shell directory to current
            shell_exec(escapeshellcmd('cd ' . __DIR__));
        },
        'description' => 'Changing directory to ' . __DIR__ . ' and turning on implicit flush...',
    ],
    [
        'description' => 'Testing...',
        'callback'    => function () use (&$codeceptionStatus)
        {
            $command = "php vendor" . DIRECTORY_SEPARATOR . "codeception" . DIRECTORY_SEPARATOR . "codeception"
                       . DIRECTORY_SEPARATOR
                       . "codecept run --coverage --coverage-xml --coverage-html --coverage-text --fail-fast";
            passthru($command, $codeceptionStatus);
        },
    ],
    [
        'description' => 'Add changes to Git...',
        'command'     => 'git add tests/*',
    ],
];

//Executing commands and show output
call_user_func_array($config['commandExecutor'], [$commands]);

exit($codeceptionStatus);