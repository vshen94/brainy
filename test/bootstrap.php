<?php

define('SMARTY_DIR', 'src/Brainy/');
require_once SMARTY_DIR . 'SmartyBC.class.php';

class SmartyTests {
    public static $cwd = null;
    public static $smarty = null;
    public static $smartyBC = null;
    public static $smartyBC31 = null;

    protected static function _init($smarty) {
        $smarty->setTemplateDir(realpath('test' . DS . 'templates' . DS));
        $smarty->setCompileDir(realpath('test' . DS . 'compiled' . DS));
        $smarty->setPluginsDir(SMARTY_PLUGINS_DIR);
        $smarty->setCacheDir(realpath('test' . DS . 'cache' . DS));
        $smarty->setConfigDir(realpath('test' . DS . 'configs' . DS));
        $smarty->template_objects = array();
        $smarty->config_vars = array();
        Smarty::$global_tpl_vars = array();
        $smarty->template_functions = array();
        $smarty->tpl_vars = array();
        $smarty->force_compile = false;
        $smarty->force_cache = false;
        $smarty->auto_literal = true;
        $smarty->caching = false;
        Smarty::$_smarty_vars = array();
        $smarty->registered_plugins = array();
        $smarty->default_plugin_handler_func = null;
        $smarty->registered_objects = array();
        $smarty->default_modifiers = array();
        $smarty->registered_filters = array();
        $smarty->autoload_filters = array();
        $smarty->escape_html = false;
        $smarty->use_sub_dirs = false;
        $smarty->config_overwrite = true;
        $smarty->config_booleanize = true;
        $smarty->config_read_hidden = true;
        $smarty->security_policy = null;
        $smarty->left_delimiter = '{';
        $smarty->right_delimiter = '}';
        $smarty->enableSecurity();
        $smarty->error_reporting = null;
        $smarty->error_unassigned = true;
        $smarty->caching_type = 'file';
        $smarty->cache_locking = false;
        $smarty->cache_id = null;
        $smarty->compile_id = null;
        $smarty->default_resource_type = 'file';
    }

    public static function init() {
        chdir(self::$cwd);
        error_reporting(E_ALL | E_STRICT);
        self::_init(SmartyTests::$smarty);
        self::_init(SmartyTests::$smartyBC);
        Smarty_Resource::$sources = array();
        Smarty_Resource::$compileds = array();
    }
}

SmartyTests::$cwd = getcwd();
SmartyTests::$smarty = new Smarty();
SmartyTests::$smartyBC = new SmartyBC();

ini_set('error_reporting', E_STRICT);
ini_set('max_execution_time', 800);
ini_set('date.timezone', 'UTC');
ini_set('memory_limit', '3500M');