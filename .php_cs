<?php

if (!class_exists('PhpCsFixer\Config', true)) {
    fwrite(STDERR, "Your php-cs-version is outdated: please upgrade it.\n");
    die(16);
}

$rules = [
    '@Symfony' => true,
    'array_syntax' => ['syntax' => 'short'],
    'blank_line_after_opening_tag' => false,
    'cast_spaces' => ['space' => 'single'],
    'concat_space' => ['spacing' => 'one'],
    'no_blank_lines_before_namespace' => true,
    'phpdoc_align' => false,
    'single_blank_line_before_namespace' => false,
    'yoda_style' => false,
];

$fixerFactory = PhpCsFixer\FixerFactory::create()->registerBuiltInFixers();
foreach (array_keys($rules) as $ruleName) {
    if ($ruleName[0] !== '@') {
        if (!$fixerFactory->hasRule($ruleName)) {
            unset($rules[$ruleName]);
        }
    }
}

return PhpCsFixer\Config::create()
    ->setCacheFile(__DIR__.'/.php_cs.cache')
    ->setRules($rules)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in([__DIR__.'/src', __DIR__.'/bootstrap'])
    );
