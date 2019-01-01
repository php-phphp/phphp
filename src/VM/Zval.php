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

abstract class Zval
{
    public static function factory($value = null)
    {
        if ($value instanceof Zval\Value) {
            return $value;
        } elseif ($value instanceof Zval\Ptr) {
            return $value->getZval();
        }
        $zval = new Zval\Value($value);
        return $zval;
    }

    public static function ptrFactory($value = null)
    {
        if (!$value instanceof Zval) {
            $value = static::factory($value);
        }
        return new Zval\Ptr($value);
    }

    public static function lockedPtrFactory($value = null)
    {
        if (!$value instanceof Zval\Value) {
            $value = static::factory($value);
        }
        return new Zval\LockedPtr($value);
    }

    public static function variableFactory(Zval $name, Zval $class = null, $scope = null)
    {
        return new Zval\Variable($name, $class, $scope);
    }

    public static function iteratorFactory(\Traversible $iterator = null)
    {
        return new Zval\Iterator($iterator);
    }
}
