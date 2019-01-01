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

namespace PHPHP\VM\Extension;

use PHPHP\VM;

abstract class Base implements \PHPHP\VM\Extension
{
    protected $isInternal = false;

    protected $name = '';

    protected $namespace = '';

    public function register(\PHPHP\VM\Executor $executor)
    {
        $functionStore = $executor->getFunctionStore();
        foreach ($this->getFunctions() as $name => $functionData) {
            $functionStore->register($name, $functionData);
        }
        $constantStore = $executor->getConstantStore();
        foreach ($this->getConstants() as $name => $value) {
            $constantStore->register($name, VM\Zval::factory($value));
        }
        $classStore = $executor->getClassStore();
        foreach ($this->getClasses() as $ce) {
            $classStore->register($ce);
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function isInternal()
    {
        return $this->isInternal;
    }

    protected function getFunctions()
    {
        return [];
    }

    protected function getConstants()
    {
        return [];
    }

    protected function getClasses()
    {
        return [];
    }
}
