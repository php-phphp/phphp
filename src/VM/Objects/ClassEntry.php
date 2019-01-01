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

namespace PHPHP\VM\Objects;

use PHPHP\VM\ConstantStore;
use PHPHP\VM\ExecuteData;
use PHPHP\VM\FunctionStore;
use PHPHP\VM\Scope;
use PHPHP\VM\Zval;
use PHPHP\VM\Zval\Ptr;

class ClassEntry
{
    private $name;

    private $properties;

    private $methods;

    private $constants;

    private $parent;

    public function __construct($name, ClassEntry $parent = null)
    {
        $this->properties = [];
        $this->staticProperties = [];
        $this->methods = new FunctionStore;
        $this->constants = new ConstantStore;
        $this->name = $name;
        $this->parent = $parent;
    }

    public function isInstanceOf($name)
    {
        $parent = $this;
        $name = strtolower($name);
        do {
            if ($name == strtolower($parent->name)) {
                return true;
            }
        } while ($parent = $parent->parent);
        return false;
    }

    public function getName()
    {
        return $this->name;
    }

    public function declareProperty($name, Zval $defaultValue, $access)
    {
        if (Scope::isStatic($access)) {
            if (isset($this->staticProperties[$name])) {
                throw new \Exception(sprintf('Cannot redeclare %s::$%s', $this->name, $name));
            }
            $this->staticProperties[$name] = $defaultValue;
        } else {
            if (isset($this->properties[$name])) {
                throw new \Exception(sprintf('Cannot redeclare %s::$%s', $this->name, $name));
            }
            $this->properties[$name] = $defaultValue;
        }
    }

    public function getMethodStore()
    {
        return $this->methods;
    }

    public function getConstructor()
    {
        $parent = $this;
        do {
            $ms = $parent->getMethodStore();
            if ($ms->exists('__construct')) {
                return $ms->get('__construct');
            }
            $className = $parent->getName();
            if ($ms->exists($className)) {
                return $ms->get($className);
            }
        } while ($parent = $parent->getParent());
    }

    public function defineConstant($name, Zval $value)
    {
        $this->constants->register($name, $value->getZval());
    }

    public function getConstantStore()
    {
        return $this->constants;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function instantiate(ExecuteData $data, array $properties)
    {
        $parent = $this;
        do {
            $properties = array_merge($parent->properties, $properties);
        } while ($parent = $parent->parent);

        $instance = new ClassInstance($this, $properties);

        return $instance;
    }

    public function findMethod($name)
    {
        $parent = $this;
        do {
            $exists = $parent->methods->exists($name);
        } while (!$exists && ($parent = $parent->parent));

        if (!$exists) {
            throw new \RuntimeException('Call To Undefined Function ' . $name);
        }

        return $parent->methods->get($name);
    }

    public function callMethod(ExecuteData $data, ClassInstance $ci = null, $name = '', array $args = [], Ptr $result = null)
    {
        $method = $this->findMethod($name);
        if (!$result) {
            $result = Zval::ptrFactory();
        }
        $method->execute($data->executor, $args, $result, $ci);
    }

    public function fetchStaticVariable($name)
    {
        $ci = $this;
        do {
            if (isset($ci->staticProperties[$name])) {
                return $ci->staticProperties[$name];
            }
        } while ($ci = $ci->parent);
        throw new \RuntimeException(sprintf('Access to undeclared static property: %s::$%s', $this->name, $name));
    }
}
