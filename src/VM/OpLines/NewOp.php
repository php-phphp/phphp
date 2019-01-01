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

use PHPHP\VM;
use PHPHP\VM\Zval;

class NewOp extends \PHPHP\VM\OpLine
{
    public $noConstructorJumpOffset;

    protected static $instanceNumber = 0;

    public function execute(\PHPHP\VM\ExecuteData $data)
    {
        self::$instanceNumber++;
        $className = $this->op1->toString();
        $classEntry = $data->executor->getClassStore()->get($className);
        $instance = $classEntry->instantiate($data, []);
        $instance->setInstanceNumber(self::$instanceNumber);
        $constructor = $classEntry->getConstructor();
        if ($constructor) {
            $data->executor->executorGlobals->call = new VM\FunctionCall($data->executor, $constructor, $instance);
        }
        $this->result->setValue(Zval::factory($instance));
        if (!$constructor) {
            $data->jump($this->noConstructorJumpOffset);
        } else {
            $data->nextOp();
        }
    }
}
