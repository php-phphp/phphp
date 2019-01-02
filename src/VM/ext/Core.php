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

function var_dump_internal(Executor $executor, Zval $arg, $indent = '')
{
    if ($arg->getValue() instanceof FunctionDataInterface) {
        return '';
    }
    $output = $indent;
    switch ($arg->getType()) {
        case 'NULL':
            $output .= 'NULL';
            break;
        case 'string':
            $length = strlen($arg->getValue());
            $output .= 'string(' . $length . ') "' . $arg->getValue() . '"';
            break;
        case 'integer':
            $output .= 'int(' . $arg->getValue() . ')';
            break;
        case 'double':
            $output .= 'float(' . $arg->getValue() . ')';
            break;
        case 'boolean':
            $output .= 'bool(' . ($arg->getValue() ? 'true' : 'false') . ')';
            break;
        case 'array':
            $array = $arg->getArray();
            $output .= 'array(' . count($array) . ") {\n";
            $newIndent = $indent . '  ';
            foreach ($array as $key => $value) {
                if (is_string($key)) {
                    $output .= $newIndent . "[\"$key\"]=>\n";
                } else {
                    $output .= $newIndent . "[$key]=>\n";
                }
                $output .= var_dump_internal($executor, $value, $newIndent);
            }
            $output .= $indent . '}';
            break;
        case 'object':
            $ci = $arg->getValue();
            $props = $ci->getProperties();
            $output = 'object(' . $ci->getClassEntry()->getName() . ')#' . $ci->getInstanceNumber();
            $output .= ' (' . count($props) . ") {\n";
            $newIndent = $indent . '  ';
            foreach ($props as $key => $value) {
                if (is_string($key)) {
                    $output .= $newIndent . "[\"$key\"]=>\n";
                } else {
                    $output .= $newIndent . "[$key]=>\n";
                }
                $output .= var_dump_internal($executor, $value, $newIndent);
            }
            $output .= $indent . '}';
            break;
    }
    return $output . "\n";
}

return [
    'register_shutdown_function' => new FunctionData\Internal(
        function (Executor $executor, array $args, Zval $return) {
            $callback = $args[0];
            array_shift($args);
            $cb = function (Executor $executor, $oldArgs, Zval $return) use ($callback, $args) {
                $cb = $executor->getCallback($callback);
                $cb($executor, $args, $return);
            };
            $executor->registerShutdownFunction($cb);
        },
        false,
        [
            new ParamData('callback', false, 'callable'),
            new ParamData('...', false, null, true),
        ]
    ),
    'set_time_limit' => new FunctionData\Internal(
        function (Executor $executor, array $args, Zval $return) {
            $time = $args[0]->toLong();
            $executor->executorGlobals->timeLimit = $time;
            if ($time > 0) {
                $executor->executorGlobals->timeLimitEnd = time() + $time;
            } else {
                $executor->executorGlobals->timeLimitEnd = PHP_INT_MAX;
            }
        },
        false,
        [
            new ParamData('seconds'),
        ]
    ),
    'var_dump' => new FunctionData\Internal(
        function (Executor $executor, array $args) {
            $output = $executor->getOutput();
            foreach ($args as $arg) {
                $output->write(var_dump_internal($executor, $arg));
            }
        },
        false,
        [
            new ParamData('var'),
            new ParamData('...', false, null, true),
        ]
    ),
];
