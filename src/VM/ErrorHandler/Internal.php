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

namespace PHPHP\VM\ErrorHandler;

class Internal implements \PHPHP\VM\ErrorHandler
{
    public function handle(\PHPHP\VM\Executor $executor, $level, $message, $file, $line, $extra = '', $addFunc = true)
    {
        if ($executor->executorGlobals->error_reporting & $level) {
            $prefix = static::getErrorLevelName($level);
            $func = $addFunc && $executor->executorGlobals->call ? $executor->executorGlobals->call->getName() . '(): ' : '';
            $output = sprintf('%s: %s%s in %s on line %d%s', $prefix, $func, $message, $file, $line, $extra);
            if ($executor->executorGlobals->display_errors) {
                $executor->getOutput()->write("\n$output\n", true);
            }
            if ($level & (E_PARSE | E_ERROR | E_COMPILE_ERROR | E_RECOVERABLE_ERROR)) {
                $executor->shutdown();
                throw new \PHPHP\VM\ErrorOccurredException;
            }
        }
    }
    
    public static function getErrorLevelName($level)
    {
        switch ($level) {
            case E_PARSE:
                return 'Parse error';
            case E_COMPILE_ERROR:
            case E_ERROR:
                return 'Fatal error';
            case E_RECOVERABLE_ERROR:
                return 'Catchable fatal error';
            case E_WARNING:
                return 'Warning';
            case E_NOTICE:
                return 'Notice';
            default:
                throw new \LogicException('Invalid error level specified');
        }
    }
}
