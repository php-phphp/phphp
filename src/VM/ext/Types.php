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

function gettypeBuilder($type)
{
    return new FunctionData\Internal(
        function (Executor $executor, array $args, Zval $return) use ($type) {
            $return->setValue($type === $args[0]->getType());
        },
        false,
        [new ParamData('var')]
    );
}

return [
    'is_array' => gettypeBuilder('array'),
    'is_bool' => gettypeBuilder('boolean'),
    'is_double' => gettypeBuilder('double'),
    'is_float' => gettypeBuilder('double'),
    'is_int' => gettypeBuilder('integer'),
    'is_integer' => gettypeBuilder('integer'),
    'is_long' => gettypeBuilder('integer'),
    'is_null' => gettypeBuilder('NULL'),
    'is_object' => gettypeBuilder('object'),
    'is_real' => gettypeBuilder('double'),
    'is_string' => gettypeBuilder('string'),
    'is_numeric' => new FunctionData\Internal(
        function (Executor $executor, array $args, Zval $return) {
            $return->setValue(is_numeric($args[0]->getValue()));
        },
        false,
        [new ParamData('var')]
    ),
    'is_scalar' => new FunctionData\Internal(
        function (Executor $executor, array $args, Zval $return) {
            $return->setValue(!$args[0]->isObject() && !$args[0]->isArray());
        },
        false,
        [new ParamData('var')]
    ),
    'get_class' => new FunctionData\Internal(
        function (Executor $executor, array $args, Zval $return) {
            $var = null;
            if (!isset($args[0]) || $args[0]->isNull()) {
                $var = $executor->getCurrent()->ci;
            } elseif ($args[0]->isObject()) {
                $var = $args[0]->getValue();
            }
            if (!$var) {
                throw new \RuntimeException('get_class() called without object from outside a class');
            }
            $return->setValue($var->getClassEntry()->getName());
        }
    ),
];
