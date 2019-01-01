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

class AddArrayElement extends \PHPHP\VM\OpLine
{
    public function execute(\PHPHP\VM\ExecuteData $data)
    {
        $key = $this->op1->toString();
        $array = $this->result->toArray();
        $var = Zval::ptrFactory($this->op2->getZval())->separateIfRef();
        if ($key) {
            $array[$key] = $var;
        } else {
            $array[] = $var;
        }
        $this->result->setValue($array);
        $data->nextOp();
    }
}
