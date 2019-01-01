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

class EvalOp extends \PHPHP\VM\OpLine
{
    public function execute(\PHPHP\VM\ExecuteData $data)
    {
        $fileName = $data->opArray->getFileName() . '(' . $this->lineno . ") : eval()'d code";
        $code = $this->op1->toString();
        $opCodes = $data->executor->compile('<?php ' . $code, $fileName);
        $return = $data->executor->execute($opCodes, $data->symbolTable);
        if ($return) {
            $this->result->setValue($return);
        }
        $data->nextOp();
    }
}
