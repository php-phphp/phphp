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

namespace PHPHP\VM\FunctionData;

use PHPHP\VM;

class Internal extends Base
{
    protected $callback;

    public function __construct($callback, $byRef = false, array $params = [])
    {
        $this->callback = $callback;
        $this->byRef    = $byRef;
        $this->params   = $params;
    }

    public function execute(VM\Executor $executor, array $args, VM\Zval\Ptr $return, \PHPHP\VM\Objects\ClassInstance $ci = null, \PHPHP\VM\Objects\ClassEntry $ce = null)
    {
        if ($this->checkParams($executor, $args, true)) {
            call_user_func_array($this->callback, [$executor, $args, $return, $ci, $ce]);
        } else {
            $return->setValue(null);
        }
    }
}
