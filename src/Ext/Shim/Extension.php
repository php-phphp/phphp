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

namespace PHPHP\Ext\Shim;

use PHPHP\VM\FunctionData;
use PHPHP\VM\Zval;

class Extension extends \PHPHP\VM\Extension\Base
{
    protected $isInternal = true;

    protected $name = 'Shim';

    protected $namespace = __NAMESPACE__;

    public function register(\PHPHP\VM\Executor $executor)
    {
        $aliases = require_once(__DIR__ . '/aliases.php');
        $functionStore = $executor->getFunctionStore();
        foreach ($aliases as $alias) {
            if (!$functionStore->exists($alias[0])) {
                $functionStore->register($alias[0], new FunctionData\InternalProxy($alias[0], $alias[1], $alias[2]));
            }
        }
        $this->registerConstants($executor);
    }

    protected function registerConstants(\PHPHP\VM\Executor $executor)
    {
        $store = $executor->getConstantStore();
        foreach (get_defined_constants(true) as $group => $set) {
            if ($group == 'user') {
                continue;
            }
            foreach ($set as $name => $value) {
                if (!$store->exists($name)) {
                    $store->register($name, Zval::factory($value));
                }
            }
        }
    }
}
