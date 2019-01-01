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

class ClassConstantFetch extends \PHPHP\VM\OpLine
{
    public function execute(\PHPHP\VM\ExecuteData $data)
    {
        if ($this->op1->isString()) {
            $ce = $data->executor->getClassStore()->get($this->op1->getValue());
        } elseif ($this->op1->isObject()) {
            $ce = $this->op1->getValue()->getClassEntry();
        } else {
            throw new \RuntimeException('Class name must be a valid object or a string');
        }

        $consts = $ce->getConstantStore();
        $value = $consts->get($this->op2->toString());

        $this->result->setValue($value);

        $data->nextOp();
    }
}
