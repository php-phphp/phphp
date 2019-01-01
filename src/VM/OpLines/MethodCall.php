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

class MethodCall extends \PHPHP\VM\OpLine
{
    private $objectOp;

    public function setObjectOp($objectOp)
    {
        $this->objectOp = $objectOp;
    }

    public function execute(\PHPHP\VM\ExecuteData $data)
    {
        $object = $this->objectOp->toObject($data);
        $methodName = $this->op1->toString();
        $args = $this->op2->toArray();
        $object->callMethod($data, $methodName, $args, $this->result);
        $data->nextOp();
    }
}
