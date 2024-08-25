<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Commands extends BaseConfig
{
    public $commands = [
        'module:create' => \App\Commands\CreateModule::class,
    ];
}
