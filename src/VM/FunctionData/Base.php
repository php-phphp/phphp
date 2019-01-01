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

abstract class Base implements VM\FunctionDataInterface
{
    protected $name;

    protected $byRef = false;

    protected $params = [];

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function isByRef()
    {
        return $this->byRef;
    }

    public function isArgByRef($n)
    {
        $param = $this->getParam($n);
        return $param && $param->isRef;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getParam($n)
    {
        return isset($this->params[$n]) ? $this->params[$n] : false;
    }

    protected function getFileName()
    {
        return '';
    }

    protected function checkParams(\PHPHP\VM\Executor $executor, array &$args, $checkTooMany = false)
    {
        $argNo = 0;
        $required = 0;
        $hasOptional = false;
        $has = count($args);
        $varargs = false;
        while ($param = $this->getParam($argNo)) {
            if ($param->type) {
                $error = '';
                if ($param->type == 'array') {
                    if (!isset($args[$argNo]) && !$param->isOptional) {
                        $error = 'array';
                    } elseif (!isset($args[$argNo])) {
                        // Blank intentional
                    } elseif (!$args[$argNo]->isArray() && !($args[$argNo]->isNull() && $param->isOptional)) {
                        $error = 'array';
                    }
                } elseif ($param->type == 'callable') {
                    //bypass the callable check for now...
                } else {
                    if (!isset($args[$argNo]) && !$param->isOptional) {
                        $error = "instance of {$param->type}";
                    } elseif (!isset($args[$argNo])) {
                        // Blank intentional
                    } elseif (!$args[$argNo]->isObject() && !($args[$argNo]->isNull() && $param->isOptional)) {
                        $error = "instance of {$param->type}";
                    } elseif (!$args[$argNo]->isObject()) {
                        // Blank intentional
                    } elseif (!$args[$argNo]->getValue()->getClassEntry()->isInstanceOf($param->type)) {
                        $error = "instance of {$param->type}";
                    }
                }
                if ($error) {
                    $type = 'none';
                    if (isset($args[$argNo])) {
                        $type = $args[$argNo]->getType();
                        if ($type == 'object') {
                            $type = 'instance of ' . $args[$argNo]->getValue()->getClassEntry()->getName();
                        }
                    }
                    $extra = '';
                    if ($this->getFileName()) {
                        $extra = ' and defined in ' . $this->getFileName() . ' on line ' . $param->lineno;
                    }
                    $message = 'Argument ' . ($argNo + 1) . " passed to {$this->name}() must be an $error, $type given, called";
                    $executor->raiseError(E_RECOVERABLE_ERROR, $message, $extra, false);
                }
            }
            if (!$param->isOptional) {
                $required++;
                if (!isset($args[$argNo])) {
                    $args[$argNo] = VM\Zval::ptrFactory();
                }
            } else {
                $hasOptional = true;
            }
            if ($param->name == '...') {
                $varargs = true;
            }
            $argNo++;
        }
        if ($required > $has) {
            $message = $this->name;
            $message .= '() expects ';
            $message .= $hasOptional ? 'at least' : 'exactly';
            $message .= " $required " . ($required == 1 ? 'parameter' : 'parameters');
            $message .= ", $has given";
            $executor->raiseError(E_WARNING, $message, '', false);
            return false;
        } elseif ($checkTooMany && !$varargs && $has > $argNo) {
            $message = $this->name;
            $message .= '() expects ';
            $message .= $hasOptional ? 'at most' : 'exactly';
            $message .= " $argNo " . ($argNo == 1 ? 'parameter' : 'parameters');
            $message .= ", $has given";
            $executor->raiseError(E_WARNING, $message, '', false);
            return false;
        }
        return true;
    }
}
