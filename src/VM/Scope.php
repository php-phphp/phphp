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

class Scope
{
    const ACC_PRIVATE   = 0x100;

    const ACC_PROTECTED = 0x200;

    const ACC_PUBLIC    = 0x400;

    const ACC_STATIC       = 0x01;

    const ACC_ABSTRAT      = 0x02;

    const ACC_FINAL        = 0x04;

    const ACC_IMP_ABSTRACT = 0x08;

    const ACC_FINAL_CLASS = 0x40;

    const ACC_INTERFACE   = 0x80;

    const ACC_TRAIT       = 0x120;

    const ACC_INTERNAL = 0x1000;

    public static function isInternal($scope)
    {
        return 0 != ($scope & self::ACC_INTERNAL);
    }

    public static function isPrivate($scope)
    {
        return 0 != ($scope & self::ACC_PRIVATE);
    }

    public static function isProtected($scope)
    {
        return 0 != ($scope & self::ACC_PROTECTED);
    }

    public static function isPublic($scope)
    {
        return 0 != ($scope & self::ACC_PUBLIC);
    }

    public static function isStatic($scope)
    {
        return 0 != ($scope & self::ACC_STATIC);
    }

    public static function isAbstract($scope)
    {
        return 0 != ($scope & self::ACC_ABSTRACT);
    }

    public static function isFinal($scope)
    {
        return 0 != ($scope & self::ACC_FINAL);
    }

    public static function isImplicitAbstract($scope)
    {
        return 0 != ($scope & self::ACC_IMP_ABSTRACT);
    }

    public static function isFinalClass($scope)
    {
        return 0 != ($scope & self::ACC_FINAL_CLASS);
    }
}
