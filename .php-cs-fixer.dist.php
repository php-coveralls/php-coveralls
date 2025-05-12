<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->ignoreDotFiles(false)
    ->ignoreVCSIgnored(true)
    ->exclude('tests/Fixture')
    ->in(__DIR__)
;

$config = new Config();
$config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        '@PHP70Migration' => true,
        'method_chaining_indentation' => false, // does not fit style of codebase
        'php_unit_test_annotation' => false, // does not fit style of codebase
        'php_unit_data_provider_return_type' => false, // for low-level PHP support
        'ternary_to_elvis_operator' => false, // for low-level PHP support
        'visibility_required' => ['elements' => ['method', 'property']], // for low-level PHP support
        'nullable_type_declaration_for_default_null_value' => ['use_nullable_type_declaration' => false], // to support low-end PHP versions
    ])
    ->setFinder($finder)
;

return $config;
