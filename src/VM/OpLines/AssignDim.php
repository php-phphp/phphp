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

class AssignDim extends \PHPHP\VM\OpLine
{
    public function execute(\PHPHP\VM\ExecuteData $data)
    {
        $this->op1->separateIfNotRef();
        if (!$this->op1->isArray()) {
            if ($this->op1->isNull()) {
                $this->op1->setValue([]);
            } else {
                throw new \RuntimeException('Cannot use a scalar value as an array');
            }
        }
        $array =& $this->op1->getArray();
        if ($this->dim) {
            $key = $this->dim->toString();
            if (isset($array[$key])) {
                $array[$key]->forceValue($this->op2->getZval());
            } else {
                $array[$key] = Zval::ptrFactory($this->op2->getZval());
            }
        } else {
            $array[] = Zval::ptrFactory($this->op2->getZval());
        }
        if ($this->result) {
            $this->result->setValue($this->op2->getZval());
        }

        $data->nextOp();
    }
}
