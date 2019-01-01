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

class ParamData
{
    public $name;

    public $isOptional = false;

    public $isRef = false;

    public $type = null;

    public $lineno = -1;

    public function __construct($name, $isRef = false, $type = null, $isOptional = false, $lineno = -1)
    {
        $this->name = $name;
        $this->isRef = $isRef;
        $this->type = $type;
        $this->isOptional = $isOptional;
        $this->lineno = $lineno;
    }
}
