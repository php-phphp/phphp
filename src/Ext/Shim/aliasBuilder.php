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

$funcs = get_defined_functions();

$output = "<?php
use PHPHP\VM\ParamData;

return array(\n";
foreach ($funcs['internal'] as $func) {
    $output .= "    array('$func', ";
    $r = new \ReflectionFunction($func);
    $output .= $r->returnsReference() ? 'true' : 'false';
    $output .= ', array(';
    foreach ($r->getParameters() as $param) {
        $output .= "new ParamData('" . $param->getName() . "', ";
        $output .= $param->isPassedByReference() ? 'true' : 'false';
        if ($param->isArray()) {
            $output .= ", 'array', ";
        } else {
            $output .= ", '', ";
        }
        $output .= ($param->isOptional() ? 'true' : 'false');
        $output .= '), ';
    }
    $output .= ")),\n";
}
$output .= ');';
file_put_contents(__DIR__ . '/aliases.php', $output);
