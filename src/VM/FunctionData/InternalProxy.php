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

namespace PHPHP\VM\FunctionData;

use PHPHP\VM;

class InternalProxy extends Base
{
    protected $callback;

    public function __construct($callback, $byRef = false, array $params = [])
    {
        $this->callback = $callback;
        $this->byRef = $byRef;
        $this->params = $params;
    }

    public function execute(VM\Executor $executor, array $args, VM\Zval\Ptr $return = null, \PHPHP\VM\Objects\ClassInstance $ci = null, \PHPHP\VM\Objects\ClassEntry $ce = null)
    {
        $rawArgs = $this->compileArguments($args);
        ob_start();
        $ret = call_user_func_array($this->callback, $rawArgs);
        $out = ob_get_clean();
        if ($out) {
            $executor->getOutput()->write($out);
        }
        if ($return) {
            $return->setValue($this->compileReturn($ret));
        }
    }

    public function compileReturn($value)
    {
        if (is_array($value)) {
            $result = [];
            foreach ($value as $key => $item) {
                $result[$key] = $this->compileReturn($item);
            }
            return VM\Zval::factory($result);
        }
        return VM\Zval::factory($value);
    }

    public function compileArguments(array $args)
    {
        $ret = [];
        foreach ($args as $key => $value) {
            if ($value->isArray()) {
                $tmp = $this->compileArguments($value->toArray());
            } else {
                $tmp = $value->getValue();
            }
            if ($value->isRef()) {
                $ret[$key] =& $tmp;
            } else {
                $ret[$key] = $tmp;
            }
            unset($tmp);
        }
        return $ret;
    }
}
