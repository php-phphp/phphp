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

class OpArray implements \ArrayAccess, \IteratorAggregate, \Countable
{
    /** @var OpLine[] */
    protected $opLines = [];

    protected $numOps = 0;

    protected $compiledVariables = [];

    protected $executor;

    /** @var BreakContinueInfo[] */
    protected $breakContinueInfo = [];

    protected $currentBCPos = -1;

    protected $numBC = 0;

    protected $fileName = '';

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    public function count()
    {
        return count($this->opLines);
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function addCompiledVariable(Zval\Variable $variable)
    {
        $this->compiledVariables[] = $variable;
    }

    public function getCompiledVariables()
    {
        return $this->compiledVariables;
    }

    public function registerExecutor(Executor $executor)
    {
        if (!$this->executor) {
            $this->executor = $executor;
            foreach ($this->compiledVariables as $variable) {
                $variable->setExecutor($executor);
            }
        }
    }

    public function beginLoop()
    {
        $this->breakContinueInfo[$this->numBC++] = new BreakContinueInfo($this->currentBCPos);
        $this->currentBCPos = $this->numBC - 1;
    }

    public function endLoop($continueOp)
    {
        $currentBC = $this->breakContinueInfo[$this->currentBCPos];
        $currentBC->continueOp = $continueOp;
        $currentBC->breakOp = $this->getNextOffset();
        $this->currentBCPos = $currentBC->parentPos;
    }

    public function getBreakContinueInfoAtLevel($levels)
    {
        $pos = $this->currentBCPos;
        $lvl = $levels;
        do {
            if ($pos === -1) {
                throw new \Exception("Cannot break/continue $levels level" . ($levels === 1 ? '' : 's'));
            }
            $bcInfo = $this->breakContinueInfo[$pos];
            $pos = $bcInfo->parentPos;
        } while (--$lvl > 0);

        return $bcInfo;
    }

    public function offsetGet($offset)
    {
        return $this->opLines[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (null === $offset) {
            $this->opLines[$this->numOps++] = $value;
        } else {
            throw new \Exception('Can only append to an op array');
            //$this->opLines[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return $offset < $this->numOps;
    }

    public function offsetUnset($offset)
    {
        //unset($this->opLines[$offset]);
        throw new \Exception('Can not unset elements from an op array');
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->opLines);
    }

    public function getNextOffset()
    {
        return $this->numOps;
    }
}
