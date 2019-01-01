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

namespace PHPHP\VM\Zval;

use PHPHP\VM\Zval;

class LockedPtr extends Ptr
{
    public function makeRef()
    {
    }

    public function assignZval(Zval $value)
    {
        throw new \BadMethodCallException("Can't assign a locked pointer");
    }

    public function forceValue(Zval $value)
    {
        throw new \BadMethodCallException("Can't force a locked pointer");
    }

    public function setValue($value)
    {
        throw new \BadMethodCallException("Can't set a locked pointer");
    }

    public function separate()
    {
        throw new \BadMethodCallException("Can't separate a locked pointer");
    }

    public function separateIfNotRef()
    {
        throw new \BadMethodCallException("Can't separateIfNotRef a locked pointer");
    }

    public function &separateIfRef()
    {
        throw new \BadMethodCallException("Can't separateIfRef a locked pointer");
    }
}
