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

class FunctionStore
{
    /** @var FunctionDataInterface[] */
    protected $functions = [];

    public function alias($newName, $existingName)
    {
        $this->register($newName, $this->get($existingName));
    }

    public function register($name, FunctionDataInterface $func)
    {
        $name = strtolower($name);
        if (isset($this->functions[$name])) {
            throw new \RuntimeException("Function $name already defined");
        }
        $func->setName($name);
        $this->functions[$name] = $func;
    }

    public function exists($name)
    {
        return isset($this->functions[strtolower($name)]);
    }

    public function get($name)
    {
        $name = strtolower($name);
        if (!isset($this->functions[$name])) {
            throw new \RuntimeException(sprintf('Call to undefined function %s', $name));
        }

        return $this->functions[$name];
    }
    
    public function getName(FunctionDataInterface $func)
    {
        foreach ($this->functions as $name => $test) {
            if ($test === $func) {
                return $name;
            }
        }
        return '';
    }
}
