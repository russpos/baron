<?php

class Expception_Undefined extends Exception {
    public function __construct($define_name) {
        $this->name = $define_name;
        parent::__construct("Constant {$this->name} must be defined in your router file!");
    }
}

$CORE_DEFINITIONS = [
    'WWW_ROOT',
    'APP_ROOT',
    'PHP_ROOT',
    'CORE_ROOT',
    'CONFIG_PATH',
];

foreach ($CORE_DEFINITIONS as $define) {
    if (!defined($define)) {
        throw new Exception_Undefined($define);
    }
}

spl_autoload_register(function($class_name) {
    $parts = explode('_', $class_name);
    $path = '/'. implode('/', $parts) . '.php';
    if (file_exists(CORE_ROOT.'/phplib/'.$path)) {
        require CORE_ROOT.'/phplib/'.$path;
    } else {
        require PHP_ROOT.$path;
    }
});

function pr($data) {
    echo "<pre>"; print_r($data); echo "</pre>";
}
