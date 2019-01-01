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

class Constant
{
    protected $name;

    protected $value;

    protected $isCaseSensitive;

    public function __construct($name, Zval $value, $isCaseSensitive)
    {
        $this->name = $name;
        $this->value = $value;
        $this->isCaseSensitive = $isCaseSensitive;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function isCaseSensitive()
    {
        return $this->isCaseSensitive;
    }
}
