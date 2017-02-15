<?php

return Symfony\CS\Config\Config::create()
    ->setUsingCache(true)
    // use default SYMFONY_LEVEL and change the set with fixers:
    ->fixers([
        '-braces',
        '-concat_without_spaces',
        '-extra_empty_lines',
        '-hash_to_slash_comment',
        '-heredoc_to_nowdoc',
        '-no_empty_comment',
        '-operators_spaces',
        '-phpdoc_annotation_without_dot',
        '-phpdoc_no_package',
        '-php_unit_fqcn_annotation',
        '-spaces_after_semicolon',
        '-trailing_spaces',
        '-unalign_double_arrow',
        '-unalign_equals',
        'concat_with_spaces',
        'php_unit_construct',
        'php_unit_strict',
        'strict',
        'strict_param',
    ])
    ->finder(
        Symfony\CS\Finder\DefaultFinder::create()
            ->in(__DIR__ . '/tests/Satooshi')
            ->in(__DIR__ . '/src')
    )
;
