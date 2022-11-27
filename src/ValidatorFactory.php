<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 06.11.2022
 * Time: 18:23
 */

namespace AppM;

use Illuminate\Validation;
use Illuminate\Translation;
use Illuminate\Filesystem\Filesystem;

class ValidatorFactory
{
    private $factory;

    public function __construct()
    {
        $this->factory = new Validation\Factory(
            $this->loadTranslator()
        );
    }

    protected function loadTranslator()
    {
        $lang = defined('AUTH_LANG') ? AUTH_LANG : dirname(__FILE__, 2);
        $local = defined('AUTH_LANG_LOCAL') ? AUTH_LANG_LOCAL : 'en';
        $filesystem = new Filesystem();
        $loader = new Translation\FileLoader($filesystem, $lang . '/lang');
        $loader->addNamespace('lang', $lang . '/lang');
        $loader->load($local, 'validation', 'lang');
        return new Translation\Translator($loader, $local);
    }

    public function __call($method, $args)
    {
        return call_user_func_array(
            [$this->factory, $method],
            $args
        );
    }
}
