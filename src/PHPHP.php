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

    public function __construct()
    {
        $functions = new VM\FunctionStore;
        $constants = new VM\ConstantStore;
        $classes = new VM\ClassStore;

        $this->executor = new VM\Executor($functions, $constants, $classes);
        $this->executor->setOutput(new VM\Output\Std($this->executor));

        $this->registerExtension(new VM\CoreExtension);
        $this->registerExtension(new Ext\Strings\Extension);
    }

    public function registerExtension(VM\Extension $extension)
    {
        $this->executor->registerExtension($extension);
    }

    public function registerExtensionByName($name)
    {
        $class = __NAMESPACE__ . '\Ext\\' . $name . '\Extension';
        if (class_exists($class)) {
            $this->executor->registerExtension(new $class);
        } else {
            throw new \RuntimeException('Could not find extension: ' . $name);
        }
    }

    public function setCWD($dir)
    {
        $this->executor->executorGlobals->cwd = $dir;
    }

    public function execute($code)
    {
        try {
            $opCodes = $this->executor->compile($code, 'Command line code');
        } catch (VM\ErrorOccurredException $e) {
            die();
        }
        return $this->executeOpLines($opCodes);
    }

    public function executeFile($file)
    {
        if (empty($file)) {
            throw new \RuntimeException('Filename must not be empty');
        }
        $this->setCWD(dirname($file));
        try {
            $opCodes = $this->executor->compileFile($file);
        } catch (VM\ErrorOccurredException $e) {
            die();
        }
        return $this->executeOpLines($opCodes);
    }

    public function executeOpLines(VM\OpArray $opCodes)
    {
        try {
            $retval = $this->executor->execute($opCodes);
            if ($retval) {
                return $retval->getValue();
            }
            $this->executor->shutdown();
            $this->executor->getOutput()->finish();
        } catch (VM\ErrorOccurredException $e) {
            // Ignore, since the error should be in the OB
        }
        $this->executor->getOutput()->finish(true);
        // Force outputting of any remaining buffers
        return null;
    }
}
