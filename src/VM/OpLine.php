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

abstract class OpLine
{
    public $op1;

    public $op2;

    public $result;

    public $lineno= 0;

    public function __construct($startLine, $op1 = null, $op2 = null, $result = null)
    {
        $this->op1       = $op1;
        $this->op2       = $op2;
        $this->result    = $result;
        if (!is_int($startLine)) {
            throw new \LogicException('Expecting int');
        }
        $this->lineno    = (int) $startLine;
    }

    public function getName()
    {
        return substr(get_class($this), strlen(__NAMESPACE__) + strlen('\OpLines\\'));
    }

    abstract public function execute(\PHPHP\VM\ExecuteData $data);
}
