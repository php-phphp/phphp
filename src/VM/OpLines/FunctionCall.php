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

class FunctionCall extends \PHPHP\VM\OpLine
{
    public function execute(\PHPHP\VM\ExecuteData $data)
    {
        $functionCall = $data->executor->executorGlobals->call;
        $args = [];
        $stack = $data->executor->getStack();
        for ($i = $stack->count() - 1; $i >= 0; $i--) {
            $args[] = $stack->pop();
        }
        $args = array_reverse($args);

        if (!$this->result) {
            $this->result = Zval::ptrFactory();
        }
        $functionCall->execute($args, $this->result);

        $data->executor->executorGlobals->call = null;
        
        $data->nextOp();
    }
}
