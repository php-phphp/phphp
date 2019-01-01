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

namespace PHPHP\VM\Zval;

use PHPHP\VM\Zval;

class Variable extends Zval
{
    const SCOPE_LOCAL = 1;

    const SCOPE_GLOBAL = 2;

    protected $name;

    protected $class;

    protected $zval;

    protected $executor;

    public function __construct(Zval $name, Zval $class = null, $scope = null)
    {
        $this->name = $name;
        $this->class = $class;

        if (null === $scope) {
            $scope = self::SCOPE_LOCAL;
        }
        $this->scope = $scope;
    }

    public function __call($method, $args)
    {
        $this->fetch();
        return call_user_func_array([$this->zval, $method], $args);
    }

    public function &getArray()
    {
        $this->fetch();
        $ret = &$this->zval->getArray();
        return $ret;
    }

    protected function fetch()
    {
        $varName = $this->name->toString();
        if ($this->class) {
            if ($this->class->isString()) {
                $ci = $this->executor->getClassStore()->get($this->class->getValue());
            } elseif ($this->class->isObject()) {
                $ci = $this->class->getValue()->getClassEntry();
            } else {
                throw new \RuntimeException('Class name must be a valid object or a string');
            }
            $this->zval = $ci->fetchStaticVariable($varName);
        } elseif (self::SCOPE_GLOBAL === $this->scope) {
            $symbolTable = $this->executor->executorGlobals->symbolTable;
            if (!isset($symbolTable[$varName])) {
                $this->zval = Zval::ptrFactory();
            } else {
                $this->zval = $symbolTable[$varName];
            }
        } elseif ($varName == 'this') {
            $this->zval = Zval::lockedPtrFactory($this->executor->getCurrent()->ci);
        } else {
            $this->zval = $this->executor->getCurrent()->fetchVariable($varName);
        }
    }

    public function setExecutor(\PHPHP\VM\Executor $executor)
    {
        $this->executor = $executor;
    }

    public function getName()
    {
        return $this->name->toString();
    }
}
