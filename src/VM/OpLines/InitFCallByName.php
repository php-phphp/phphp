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

class InitFCallByName extends \PHPHP\VM\OpLine
{
    public function execute(\PHPHP\VM\ExecuteData $data)
    {
        $ci = $this->op1;
        $funcName = $this->op2->toString();
        if ($ci) {
            if ($ci->isObject()) {
                $ci = $ci->getValue();
                $functionData = $ci->getClassEntry()->getMethodStore()->get($funcName);
            } else {
                throw new \RuntimeException(sprintf('Call to a member function %s() on a non-object', $funcName));
            }
        } else {
            $functionData = $data->executor->getFunctionStore()->get($funcName);
        }
        
        $data->executor->executorGlobals->call = new VM\FunctionCall($data->executor, $functionData, $ci);
        
        $data->nextOp();
    }
}
