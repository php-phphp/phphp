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

namespace PHPHP\VM;

interface FunctionDataInterface
{
    public function execute(Executor $executor, array $args, \PHPHP\VM\Zval\Ptr $return, \PHPHP\VM\Objects\ClassInstance $ci = null, \PHPHP\VM\Objects\ClassEntry $ce = null);

    public function isByRef();

    public function isArgByRef($n);

    public function getParam($n);

    public function getParams();
}
