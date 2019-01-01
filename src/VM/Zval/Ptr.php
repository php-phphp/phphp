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

class Ptr extends Zval
{
    protected $zval;

    public function __construct(Value $zval)
    {
        $this->zval = $zval;
        $zval->addRef();
    }

    public function __destruct()
    {
        if ($this->zval instanceof Value) {
            $this->zval->delRef();
        }
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->zval, $method], $args);
    }

    public function isVariable()
    {
        if ($this->zval instanceof Variable) {
            return true;
        } elseif ($this->zval instanceof Ptr) {
            return $this->zval->isVariable();
        }
        return false;
    }

    public function &getArray()
    {
        $ret = &$this->zval->getArray();
        return $ret;
    }

    public function getPtr()
    {
        if ($this->zval instanceof Variable) {
            return $this->zval->getPtr();
        }
        return $this;
    }

    public function getZval()
    {
        $tmp = $this->zval;
        do {
            $value = $tmp;
            $tmp = $value->getZval();
        } while ($value !== $tmp);
        return $value;
    }

    public function getImmediateZval()
    {
        return $this->zval;
    }

    public function makeRef()
    {
        if ($this->zval instanceof Variable) {
            return $this->zval->makeRef();
        }
        if (!$this->zval->isRef()) {
            $this->separateIfNotRef();
            $this->zval->makeRef();
        }
    }

    public function assignZval(Zval $value)
    {
        if ($value instanceof Ptr) {
            $value = $value->getZval();
        }
        $this->zval = $value;
    }

    public function forceValue(Zval $value)
    {
        if ($this->zval instanceof Variable) {
            return $this->zval->forceValue($value);
        }
        $value = $value->getZval();
        $this->zval->delRef();
        $this->zval = $value;
        $this->zval->addRef();
    }

    public function setValue($value)
    {
        if ($value instanceof Zval) {
            $value = $value->getZval();
        }
        if ($this->zval instanceof Variable || $this->zval instanceof VariableList) {
            return $this->zval->setValue($value);
        }
        if ($value instanceof Zval) {
            if ($this->zval->isRef()) {
                $this->zval->setValue($value);
            } elseif ($value->isRef()) {
                $this->separateIfNotRef();
                $this->zval->setValue($value);
            } elseif ($this->zval->getRefcount() == 1) {
                $this->zval->setValue($value);
            } else {
                $this->forceValue($value);
            }
        } elseif ($value !== $this->zval->getValue()) {
            $this->separateIfNotRef();
            $this->zval->setValue($value);
        }
    }

    public function separate()
    {
        if ($this->zval instanceof Variable) {
            return $this->zval->separate();
        }
        if ($this->zval->getRefcount() > 1) {
            $this->zval = clone $this->zval;
        }
        return $this;
    }

    public function separateIfNotRef()
    {
        if ($this->zval instanceof Variable) {
            return $this->zval->separateIfNotRef();
        }
        if (!$this->zval->isRef()) {
            $this->separate();
        }
        return $this;
    }

    public function &separateIfRef()
    {
        if ($this->zval instanceof Variable) {
            return $this->zval->separateIfRef();
        }
        if ($this->zval->isRef()) {
            $this->separate();
        }
        return $this;
    }
}
