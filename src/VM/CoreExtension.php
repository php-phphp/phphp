<?php declare(strict_types=1);
/**
 * This file is part of the php-lisp/php-lisp.
 *
 * @Link     https://github.com/php-phphp/phphp
 * @Document https://github.com/php-phphp/phphp/blob/master/README.md
 * @Contact  itwujunze@gmail.com
 * @License  https://github.com/php-phphp/phphp/blob/master/LICENSE
 *
 * (c) Panda <itwujunze@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PHPHP\VM;

use PHPHP\VM\Objects\ClassEntry;

final class CoreExtension extends Extension\Base
{
    protected $isInternal = true;

    protected $name = 'Core';

    protected $namespace = __NAMESPACE__;

    public function register(\PHPHP\VM\Executor $executor)
    {
        $this->registerCoreConstants($executor->getConstantStore());
        parent::register($executor);
    }

    protected function registerCoreConstants(ConstantStore $constants)
    {
        $coreConstants = [
            'null'  => null,
            'true'  => true,
            'false' => false,
        ];
        foreach ($coreConstants as $name => $value) {
            $constants->register($name, Zval::factory($value), false);
        }
    }
    
    protected function registerCoreFunctions()
    {
        $ret = [
            'array_merge' => new FunctionData\InternalProxy(
                'array_merge',
                false,
                [
                    new ParamData('array', false, 'array')
                ]
            ),
            'bin2hex' => new FunctionData\InternalProxy(
                'bin2hex',
                false,
                [
                    new ParamData('str')
                ]
            ),
        ];
        $aliases = [
            'dirname',
            'explode',
            'php_uname',
            'phpversion',
            'printf',
            'print_r',
            'realpath',
            'strlen',
            'strpos',
            'substr',
            'trim',
            'var_dump',
            'zend_version',
        ];
        foreach ($aliases as $alias) {
            $ret[$alias] = new FunctionData\InternalProxy($alias);
        }
        return $ret;
    }

    protected function getFunctions()
    {
        $funcs = require_once __DIR__ . '/ext/Functions.php';
        $funcs += require_once __DIR__ . '/ext/Core.php';
        $funcs += require_once __DIR__ . '/ext/Array.php';
        $funcs += require_once __DIR__ . '/ext/Types.php';
        $funcs += require_once __DIR__ . '/ext/OutputBuffer.php';
        return $funcs + $this->registerCoreFunctions();
    }

    protected function getConstants()
    {
        return [
            'PHP_INT_SIZE' => PHP_INT_SIZE,
            'PHP_SAPI'     => 'cli',
            'PHP_OS'       => PHP_OS,
            'PHP_VERSION'  => PHP_VERSION,
            'E_ERROR'      => E_ERROR,
            'E_ALL'        => E_ALL,

            'PHP_OUTPUT_HANDLER_START' => PHP_OUTPUT_HANDLER_START,
            'PHP_OUTPUT_HANDLER_END'   => PHP_OUTPUT_HANDLER_END,

        ];
    }

    protected function getClasses()
    {
        $classes = [
            new ClassEntry('stdClass'),
        ];
        $tmp = require_once __DIR__ . '/ext/Closure.php';
        foreach ($tmp as $class => $props) {
            $ce = new ClassEntry($class);
            $ms = $ce->getMethodStore();
            foreach ($props['methods'] as $name => $fe) {
                $ms->register($name, $fe);
            }
            foreach ($props['properties'] as $name => $props) {
                $ce->declareProperty($name, $props['default'], $props['access']);
            }
            $classes[] = $ce;
        }
        return $classes;
    }
}
