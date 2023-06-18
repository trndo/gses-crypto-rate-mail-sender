<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'full_opening_tag' => false,
        'php_unit_construct' => true,
        'php_unit_strict' => true,
        'phpdoc_order' => true,
        'declare_strict_types' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'not_operator_with_space' => true,
    ])
    ->setUsingCache(false)
    ->setFinder($finder)
;
