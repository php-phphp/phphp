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

class InitStaticMethodCall extends \PHPHP\VM\OpLine
{
    public function execute(\PHPHP\VM\ExecuteData $data)
    {
        $className = $this->op1;
        $funcName = $this->op2->toString();
        if ($className->isString()) {
            $ce = $data->executor->getClassStore()->get($className->getValue());
        } elseif ($className->isObject()) {
            $ce = $className->getValue()->getClassEntry();
        } else {
            throw new \RuntimeException('Class name must be a valid object or a string');
        }

        $functionData = $ce->getMethodStore()->get($funcName);
        
        $data->executor->executorGlobals->call = new VM\FunctionCall($data->executor, $functionData, null, $ce);
        
        $data->nextOp();
    }
}
