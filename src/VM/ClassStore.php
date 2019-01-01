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

use PHPHP\VM\Objects\ClassEntry;

class ClassStore
{
    /** @var ClassEntry[] */
    protected $classes = [];

    public function register(ClassEntry $ce)
    {
        $lcname = strtolower($ce->getName());
        if (isset($this->classes[$lcname])) {
            throw new \RuntimeException(sprintf('Class %s already defined', $ce->getName()));
        }
        $this->classes[$lcname] = $ce;
    }

    public function exists($name)
    {
        return isset($this->classes[strtolower($name)]);
    }

    public function get($name)
    {
        $name = strtolower($name);
        if (!isset($this->classes[$name])) {
            throw new \RuntimeException(sprintf('Undefined class %s', $name));
        }

        return $this->classes[$name];
    }

    public function getNames()
    {
        return array_keys($this->classes);
    }
}
