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
        'ternary_to_elvis_operator' => false,
        'php_unit_test_annotation' => false,
        'single_line_comment_style' => false,
        'method_chaining_indentation' => false,
        'visibility_required' => false,

        'concat_space' => false,
        'php_unit_internal_class' => false,
        'explicit_string_variable' => false,
        'php_unit_test_case_static_method_calls' => false,
        'explicit_indirect_variable' => false,
        'single_blank_line_before_namespace' => false,
        'php_unit_test_class_requires_covers' => false,
        'comment_to_phpdoc' => false,
    ])
    ->setFinder($finder)
;

return $config;
