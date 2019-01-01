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

class VariableList extends Zval
{
    protected $name;

    protected $zval;

    protected $executor;

    protected $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function __call($method, $args)
    {
        throw new \Exception('Invalid Call');
        $this->zval = $this->executor->getCurrent()->fetchVariable($this->name->toString());
        return call_user_func_array([$this->zval, $method], $args);
    }

    public function addRef()
    {
    }

    public function delRef()
    {
    }

    public function isRef()
    {
        return false;
    }

    public function getZval()
    {
        return $this;
    }

    public function setValue($value)
    {
        if ($value instanceof Zval) {
            if ($value->isArray()) {
                $value = $value->toArray();
            }
        }
        if (is_array($value)) {
            foreach ($this->values as $key => $val) {
                if (isset($value[$key]) && $val) {
                    $val->setValue($value[$key]);
                } elseif ($val) {
                    $val->setValue(null);
                }
            }
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
