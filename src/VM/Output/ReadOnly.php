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

class ReadOnly extends Buffer
{
    public function __construct(\PHPHP\VM\Executor $executor)
    {
        \PHPHP\VM\Output::__construct($executor);
    }

    public function clean()
    {
        $this->executor->raiseError(E_ERROR, 'Cannot use output buffering in output buffering display handlers');
    }

    public function endFlush($force = false)
    {
        $this->executor->raiseError(E_ERROR, 'Cannot use output buffering in output buffering display handlers');
    }

    public function write($data, $isError = false)
    {
        if ($isError) {
            $this->parent->write($data);
        } else {
            $this->executor->raiseError(E_ERROR, 'Cannot use output buffering in output buffering display handlers');
        }
    }

    public function finish($force = false)
    {
        $this->executor->raiseError(E_ERROR, 'Cannot use output buffering in output buffering display handlers');
    }

    public function flush($force = false)
    {
        $this->executor->raiseError(E_ERROR, 'Cannot use output buffering in output buffering display handlers');
    }

    public function getBuffer()
    {
        $this->executor->raiseError(E_ERROR, 'Cannot use output buffering in output buffering display handlers');
    }

    public function setBuffer($data)
    {
        $this->executor->raiseError(E_ERROR, 'Cannot use output buffering in output buffering display handlers');
    }
}
