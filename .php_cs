<?php
$header = <<<EOF
This file is part of the php-lisp/php-lisp.

@Link     https://github.com/php-phphp/phphp
@Document https://github.com/php-phphp/phphp/blob/master/README.md
@Contact  itwujunze@gmail.com
@License  https://github.com/php-phphp/phphp/blob/master/LICENSE

(c) Panda <itwujunze@gmail.com>

This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.
EOF;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        'header_comment' => [
            'commentType' => 'PHPDoc',
            'header' => $header,
            'separate' => 'none'
        ],
        'array_syntax' => [
            'syntax' => 'short'
        ],
        'single_quote' => true,
        'class_attributes_separation' => true,
        'no_unused_imports' => true,
        'standardize_not_equals' => true,
        'declare_strict_types' => true,
        'ordered_imports' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'php_unit_construct' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('vendor')
            ->in(__DIR__)
    )
    ->setUsingCache(false);
