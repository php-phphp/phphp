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

class EmptyOp extends \PHPHP\VM\OpLine
{
    public function execute(\PHPHP\VM\ExecuteData $data)
    {
        if ($this->op1->isVariable()) {
            $varName = $this->op1->getName();
            if (!isset($data->symbolTable[$varName])) {
                $this->result->setValue(true);
                $data->nextOp();
                return;
            }
        }
        $this->result->setValue(!$this->op1->getValue());
        $data->nextOp();
    }
}
