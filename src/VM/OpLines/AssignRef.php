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

class AssignRef extends \PHPHP\VM\OpLine
{
    public $property;

    public $dim;

    public function execute(\PHPHP\VM\ExecuteData $data)
    {
        $this->op2->makeRef();
        $zval = $this->op2->getZval();

        if ($this->property) {
            $this->op1->getValue()->setProperty($this->property->toString(), $zval);
        } elseif ($this->dim) {
            $array =& $this->op1->getArray();
            $key = $this->property->toString();
            if (isset($array[$key])) {
                $array[$key]->forceValue($zval);
            } else {
                $array[$key] = Zval::ptrFactory($zval);
            }
        } else {
            $this->op1->forceValue($zval);
        }

        if ($this->result) {
            $this->result->setValue($zval);
        }

        $data->nextOp();
    }
}
