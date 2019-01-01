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

namespace PHPHP\Ext\Strings;

class Extension extends \PHPHP\VM\Extension\Base
{
    protected $isInternal = true;

    protected $name = 'Strings';

    protected $namespace = __NAMESPACE__;

    protected function getFunctions()
    {
        return require __DIR__ . '/functions.php';
    }
}
