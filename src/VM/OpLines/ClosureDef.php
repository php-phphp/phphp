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

class ClosureDef extends \PHPHP\VM\OpLine
{
    public function execute(\PHPHP\VM\ExecuteData $data)
    {
        $name = $this->op1->toString();
        $functionData = $this->op2;
        $ce = $data->executor->getClassStore()->get('Closure');
        $ci = $ce->instantiate($data, ['functionData' => Zval::ptrFactory($functionData)]);
        if ($this->result) {
            $this->result->setValue($ci);
        }

        $data->nextOp();
    }
}
