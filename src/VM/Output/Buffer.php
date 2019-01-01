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

use PHPHP\VM\Zval;

class Buffer extends \PHPHP\VM\Output
{
    protected $buffer = '';

    protected $callback = null;

    protected $mode = PHP_OUTPUT_HANDLER_START;

    public function __construct(\PHPHP\VM\Executor $executor, $callback = null)
    {
        parent::__construct($executor);
        if ($callback && !is_callable($callback)) {
            throw new \LogicException('Non-callable callback provided');
        }
        $this->callback = $callback;
    }

    public function getCallback()
    {
        return $this->callback;
    }
    
    public function clean()
    {
        $this->mode = 0;
        $this->buffer = '';
    }

    public function end()
    {
        $this->executor->setOutput($this->parent);
    }

    public function endFlush($force = false)
    {
        $this->mode |= PHP_OUTPUT_HANDLER_END;
        $this->flush($force);
        $this->end();
    }

    public function write($data)
    {
        $this->buffer .= $data;
    }

    public function finish($force = true)
    {
        $this->endFlush($force);
        $this->parent->finish($force);
    }

    public function flush($force = false)
    {
        if ($this->callback) {
            $this->parent->write($this->callCallback($this->buffer, $this->mode));
            $this->mode = 0;
        } else {
            $this->parent->write($this->buffer);
        }
        $this->buffer = '';
    }

    public function getBuffer()
    {
        return $this->buffer;
    }

    public function setBuffer($data)
    {
        $this->buffer = $data;
    }

    protected function callCallback($data, $mode)
    {
        if ($this->callback) {
            $this->buffer = '';
            $current = $this->executor->getOutput();
            $ro = new ReadOnly($this->executor);
            $this->executor->setOutput($ro);
            try {
                $ret = Zval::ptrFactory();
                $args = [
                    Zval::ptrFactory($data),
                    Zval::ptrFactory($mode),
                ];
                $cb = $this->callback;
                $cb($this->executor, $args, $ret);
            } catch (\PHPHP\VM\ErrorOccurredException $e) {
                // Restore error handler first!
                $this->executor->setOutput($current);
                throw $e;
            }
            $this->executor->setOutput($current);
            if ($ret->isBool() || $ret->toBool() == false) {
                return $data;
            }
            return $ret->toString();
        }
        return $data;
    }
}
