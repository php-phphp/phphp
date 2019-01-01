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

namespace PHPHP\Ext\Strings;

use PHPHP\VM\Executor;
use PHPHP\VM\FunctionData;
use PHPHP\VM\ParamData;
use PHPHP\VM\Zval;

$implode = new FunctionData\Internal(
                function (Executor $executor, array $args, Zval $return) {
                    $array = $args[0];
                    $glue = '';
                    if ($args[1]) {
                        if ($args[0]->isArray()) {
                            $glue = $args[1]->toString();
                        } else {
                            $glue = $args[0]->toString();
                            $array = $args[1];
                        }
                    }
                    if ($array->isArray()) {
                        $result = '';
                        $sep = '';
                        foreach ($array->getArray() as $value) {
                            $result .= $sep . $value->toString();
                            $sep = $glue;
                        }
                        $return->setValue($result);
                    } else {
                        var_dump($args);
                        throw new \Exception('Something failed! ' . $executor->getCurrent()->opArray->getFileName());
                    }
                },
                false,
                [
                    new ParamData('glue'),
                    new ParamData('pieces', false, '', true),
                ]
);

return [
    'implode' => $implode,
    'join'    => new FunctionData\Alias($implode),
];
