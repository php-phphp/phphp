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

class FetchGlobalVariable extends \PHPHP\VM\OpLine
{
    public function execute(\PHPHP\VM\ExecuteData $data)
    {
        $varName = $this->op1->toString();
        if (!isset($data->executor->executorGlobals->symbolTable[$varName])) {
            $data->executor->executorGlobals->symbolTable[$varName] = Zval::ptrFactory();
        }
        $data->symbolTable[$varName] = $data->executor->executorGlobals->symbolTable[$varName];
        $data->nextOp();
    }
}
