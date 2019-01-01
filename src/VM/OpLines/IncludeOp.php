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

use PHPParser_Node_Expr_Include as IncludeNode;

class IncludeOp extends \PHPHP\VM\OpLine
{
    public function execute(\PHPHP\VM\ExecuteData $data)
    {
        $fileName = $this->op2->toString();
        if (substr($fileName, 0, 1) !== '/') {
            $fileName = $data->executor->executorGlobals->cwd . '/' . $fileName;
        }
        $fileName = realpath($fileName);
        if (!is_file($fileName)) {
            throw new \RuntimeException('Including bad file!');
        }
        switch ($this->op1->getValue()) {
            case IncludeNode::TYPE_INCLUDE_ONCE:
            case IncludeNode::TYPE_REQUIRE_ONCE:
                if ($data->executor->hasFile($fileName)) {
                    break;
                }
                // no break
            case IncludeNode::TYPE_INCLUDE:
            case IncludeNode::TYPE_REQUIRE:
                $opCodes = $data->executor->compileFile($fileName);
                $data->executor->execute($opCodes);
        }

        $data->nextOp();
    }
}
