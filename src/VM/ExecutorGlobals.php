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

class ExecutorGlobals
{
    public $call = null;

    public $cwd = '';
    
    public $display_errors = true;
    
    public $error_reporting = -1;

    /**
     * The global symbol table
     * @var array The global symbol table for variables
     */
    public $symbolTable = [];
    
    public $superGlobals = [];

    public $timeLimit = 0;

    public $timeLimitEnd = PHP_INT_MAX;
    
    public function __construct()
    {
        $globalsPtr = Zval::ptrFactory([]);
        $this->symbolTable =& $globalsPtr->getArray();
        $this->symbolTable['GLOBALS'] = $globalsPtr;
    }
}
