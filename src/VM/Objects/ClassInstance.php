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

use PHPHP\VM\ExecuteData;
use PHPHP\VM\Zval;
use PHPHP\VM\Zval\Ptr;

class ClassInstance
{
    private $ce;

    private $properties = [];

    private $instanceNumber = 0;

    public function __construct(ClassEntry $ce, array $properties)
    {
        $this->ce = $ce;
        $this->properties = array_map(function ($property) {
            return Zval::ptrFactory($property->getZval());
        }, $properties);
    }

    public function getInstanceNumber()
    {
        return $this->instanceNumber;
    }

    public function setInstanceNumber($num)
    {
        $this->instanceNumber = $num;
    }

    public function getClassEntry()
    {
        return $this->ce;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function getProperty($name)
    {
        if (!isset($this->properties[$name])) {
            throw new \RuntimeException(sprintf('Undefined property: %s::%s', $this->ce->getName(), $name));
        }
        return $this->properties[$name];
    }

    public function setProperty($name, Zval $value)
    {
        if (isset($this->properties[$name])) {
            $this->properties[$name]->assignZval($value->getZval());
        } else {
            $this->properties[$name] = Zval::ptrFactory($value->getZval());
        }
    }

    public function callMethod(ExecuteData $data, $name, array $args, Ptr $result = null)
    {
        $this->ce->callMethod($data, $this, $name, $args, $result);
    }

    public function __destruct()
    {
        array_map(function ($property) {
            $property->delRef();
        }, $this->properties);
    }
}
