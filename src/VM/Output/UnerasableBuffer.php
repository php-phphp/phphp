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

namespace PHPHP\VM\Output;

class UnerasableBuffer extends Buffer
{
    public function flush($force = false)
    {
        if (!$force) {
            if ($this->callback) {
                $this->buffer = $this->callCallback($this->buffer, $this->mode);
                $this->mode = 0;
            }
            throw new \LogicException('Unflushable Buffer');
        }
        parent::flush($force);
    }
}
