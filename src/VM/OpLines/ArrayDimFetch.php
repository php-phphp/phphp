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

class ArrayDimFetch extends \PHPHP\VM\OpLine
{
    public $write = false;

    public function execute(\PHPHP\VM\ExecuteData $data)
    {
        $key = $this->op2->getValue();
        if ($this->op1->isArray()) {
            $array =& $this->op1->getArray();
            if (!isset($array[$key])) {
                if ($this->write) {
                    $array[$key] = Zval::ptrFactory();
                    $this->result->setValue($array[$key]);
                } else {
                    $this->result->setValue(Zval::ptrFactory());
                }
            } else {
                $this->result->setValue($array[$key]);
            }
        } elseif ($this->op1->isString()) {
            $value = $this->op1->getValue();
            if (isset($value[$key])) {
                $this->result->setValue($value[$key]);
            } else {
                $this->result->setValue('');
            }
        } elseif ($this->write && $this->op1->isNull()) {
            $value = Zval::ptrFactory();
            $this->op1->setValue([$key => $value]);
            $this->result->setValue($value);
        } else {
            throw new \RuntimeException('Cannot use a scalar value as an array');
        }
        if ($this->write) {
            $this->result->getZval()->makeRef();
        }
        $data->nextOp();
    }
}
