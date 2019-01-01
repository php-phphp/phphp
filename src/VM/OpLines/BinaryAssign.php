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

abstract class BinaryAssign extends \PHPHP\VM\OpLine
{
    public $dim;

    public $property;

    protected function getValue()
    {
        if ($this->property) {
            if (!$this->op1->isObject()) {
                throw new \RuntimeException('Attempt to assign property of non-object');
            }
            return $this->op1->getValue()->getProperty($this->property->toString())->getValue();
        } elseif ($this->dim) {
            $array = $this->op1->getArray();
            return $array[$this->dim->toString()]->getValue();
        }
        return $this->op1->getValue();
    }

    protected function setValue($value)
    {
        $zval = Zval::factory($value);

        if ($this->property) {
            $this->op1->getValue()->setProperty($this->property->toString(), $zval);
        } elseif ($this->dim) {
            $array =& $this->op1->getArray();
            $key = $this->dim->toString();
            if (isset($array[$key])) {
                $array[$key]->setValue($zval);
            } else {
                $array[$key] = Zval::ptrFactory($zval);
            }
        } else {
            $this->op1->setValue($zval);
        }

        if ($this->result) {
            $this->result->setValue($zval);
        }
    }
}
