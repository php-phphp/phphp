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

class ExecuteData
{
    public $executor;

    public $function;

    public $arguments;

    public $opArray = [];

    public $opLine;

    public $parent;

    public $returnValue;

    public $symbolTable = [];

    protected $opPosition = 0;

    public function __construct(Executor $executor, OpArray $opArray, FunctionDataInterface $function = null)
    {
        $this->executor = $executor;
        $this->opArray = $opArray;
        $this->opLine = $opArray[0];
        $this->function = $function;
        $this->returnValue = Zval::ptrFactory();
    }

    public function fetchVariable($name)
    {
        if (!isset($this->symbolTable[$name])) {
            $this->symbolTable[$name] = Zval::ptrFactory();
        }
        return $this->symbolTable[$name];
    }

    public function jump($position)
    {
        $this->opPosition = $position;
        if (!isset($this->opArray[$position])) {
            $this->opLine = false;
        } else {
            $this->opLine = $this->opArray[$position];
        }
    }

    public function nextOp()
    {
        $this->jump($this->opPosition + 1);
    }
}
