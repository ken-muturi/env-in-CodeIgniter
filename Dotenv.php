<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

// file: application/libraries/Dotenv.php
class DotEnv
{
    public function __construct()
    {
        $dotenv = Dotenv\Dotenv::createUnsafeImmutable(FCPATH);
        $dotenv->load();
    }
}
