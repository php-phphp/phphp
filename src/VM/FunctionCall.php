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

class FunctionCall
{
    protected $executor;

    protected $function;

    protected $ci;

    protected $ce;

    public function __construct(Executor $executor, FunctionDataInterface $function, Objects\ClassInstance $ci = null, Objects\ClassEntry $ce = null)
    {
        $this->function = $function;
        $this->ci = $ci;
        $this->ce = $ce;
        $this->executor = $executor;
    }

    public function getName()
    {
        if ($this->ci) {
            return $this->ci->getClassEntry()->getMethodStore()->getName($this->function);
        } elseif ($this->ce) {
            return $this->ce->getMethodStore()->getName($this->function);
        }
        return $this->executor->getFunctionStore()->getName($this->function);
    }

    public function execute(array $args, \PHPHP\VM\Zval $result)
    {
        $this->function->execute($this->executor, $args, $result, $this->ci, $this->ce);
    }

    public function getFunction()
    {
        return $this->function;
    }

    public function getClassInstance()
    {
        return $this->ci;
    }

    public function getClassEntry()
    {
        return $this->ce;
    }
}
