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

class AssignDiv extends BinaryAssign
{
    public function execute(\PHPHP\VM\ExecuteData $data)
    {
        $value = $this->getValue();
        if (0 == $value) {
            $this->setValue(false);
        } else {
            $this->setValue($value / $this->op2->getValue());
        }

        $data->nextOp();
    }
}
