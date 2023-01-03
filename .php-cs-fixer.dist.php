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

        'native_constant_invocation' => false,
        'native_function_invocation' => false,
        'visibility_required' => false,
        'concat_space' => false,
        'php_unit_internal_class' => false,
        'final_internal_class' => false,
        'php_unit_test_annotation' => false,
        'no_empty_phpdoc' => false,
        'no_blank_lines_after_class_opening' => false,
        'explicit_string_variable' => false,
        'string_length_to_empty' => false,
        'php_unit_test_case_static_method_calls' => false,
        'ternary_to_elvis_operator' => false,
        'ternary_operator_spaces' => false,
        'no_superfluous_phpdoc_tags' => false,
        'explicit_indirect_variable' => false,
        'multiline_whitespace_before_semicolons' => false,
        'phpdoc_order' => false,
        'phpdoc_separation' => false,
        'phpdoc_trim' => false,
        'single_line_comment_style' => false,
        'phpdoc_trim_consecutive_blank_line_separation' => false,
        'blank_line_after_opening_tag' => false,
        'single_blank_line_before_namespace' => false,
        'phpdoc_order_by_value' => false,
        'php_unit_test_class_requires_covers' => false,
        'method_chaining_indentation' => false,
        'blank_line_before_statement' => false,
        'comment_to_phpdoc' => false,
        'phpdoc_var_annotation_correct_order' => false,
        'no_extra_blank_lines' => false,
        'combine_consecutive_issets' => false,
        'no_spaces_inside_parenthesis' => false,
    ])
    ->setFinder($finder)
;

return $config;
