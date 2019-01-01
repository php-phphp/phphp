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

namespace PHPHP;

class PHPHP implements PHPHPInterface
{
    protected $executor;

    public function registerExtension(Engine\Extension $extension)
    {
        // TODO: Implement registerExtension() method.
    }

    public function registerExtensionByName($name)
    {
        // TODO: Implement registerExtensionByName() method.
    }

    public function setCWD($dir)
    {
        // TODO: Implement setCWD() method.
    }

    public function execute($code)
    {
        // TODO: Implement execute() method.
    }

    public function executeFile($file)
    {
        // TODO: Implement executeFile() method.
    }

    public function executeOpLines(Engine\OpArray $opCodes)
    {
        // TODO: Implement executeOpLines() method.
    }
}
