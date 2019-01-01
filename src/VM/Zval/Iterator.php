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

class Iterator extends Zval
{
    protected $iterator;

    public function __construct(\Traversable $iterator = null)
    {
        $this->setIterator($iterator);
    }

    public function setIterator(\Traversable $iterator = null)
    {
        $this->iterator = $iterator;
    }
    
    public function getIterator()
    {
        if ($this->iterator) {
            return $this->iterator;
        }
        return new \EmptyIterator;
    }
}
