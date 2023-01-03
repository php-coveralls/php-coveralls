<?php

$finder = PhpCsFixer\Finder::create()
    ->ignoreDotFiles(false)
    ->ignoreVCSIgnored(true)
    ->exclude('tests/Fixtures')
    ->in(__DIR__)
;

$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        'yoda_style' => [
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ],
        'concat_space' => false,
        'explicit_indirect_variable' => false,
        'explicit_string_variable' => false,
        'method_chaining_indentation' => false,
        'php_unit_test_annotation' => false,
        'single_line_comment_style' => false,
        'ternary_to_elvis_operator' => false,
        'visibility_required' => false,

        'blank_line_after_opening_tag' => false,
        'no_extra_blank_lines' => false,
        'single_blank_line_before_namespace' => false,
    ])
    ->setFinder($finder)
;

return $config;
