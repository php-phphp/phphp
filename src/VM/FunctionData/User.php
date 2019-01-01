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

class User extends Base
{
    protected $opArray;

    protected $byRef = false;

    protected $params = [];

    public function __construct(VM\OpArray $opArray, $byRef = false, array $params = [])
    {
        $this->opArray = $opArray;
        $this->byRef = $byRef;
        $this->params = $params;
    }

    protected function getFileName()
    {
        return $this->opArray->getFileName();
    }

    public function execute(VM\Executor $executor, array $args, VM\Zval\Ptr $return, \PHPHP\VM\Objects\ClassInstance $ci = null, \PHPHP\VM\Objects\ClassEntry $ce = null)
    {
        $scope = [];
        if (!$args) {
            $args = [];
        }
        $this->checkParams($executor, $args);
        if ($this->byRef) {
            $return->makeRef();
        }
        $executor->execute($this->opArray, $scope, $this, $args, $return, $ci, $ce);
    }
}
