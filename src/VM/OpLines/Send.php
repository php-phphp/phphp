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

namespace PHPHP\VM\OpLines;

use PHPHP\VM\Zval;

class Send extends \PHPHP\VM\OpLine
{
    public function execute(\PHPHP\VM\ExecuteData $data)
    {
        $ptr = null;
        if ($data->executor->executorGlobals->call->getFunction()->isArgByRef($this->op2->getValue())) {
            if ($this->op1->isVariable() || $this->op1->isRef() || $this->op1->isObject()) {
                $op = $this->op1->getPtr();
                $op->makeRef();
                $op->addRef();
                $ptr = Zval::ptrFactory($op->getZval());
            } else {
                throw new \RuntimeException("Can't pass parameter {" . $this->op2->getValue() . '} by reference');
            }
        } else {
            $ptr = Zval::ptrFactory($this->op1->getValue());
        }
        $data->executor->getStack()->push($ptr);

        $data->nextOp();
    }
}
