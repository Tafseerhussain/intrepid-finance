<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('core')
    ->exclude('app/hook/captcha')
    ->exclude('app/hook/dev')
    ->exclude('app/hook/example')
    ->exclude('app/hook/excel')
    ->exclude('app/hook/moola')
    ->exclude('app/hook/pdf')
    ->exclude('app/hook/sandbox')
    ->exclude('app/hook/test')
    ->exclude('app/hook/test-dom')
    ->exclude('app/hook/test-validate')
    ->exclude('vendor')
    ->exclude('include/system')
    ->in(array_filter([
        __DIR__,
        realpath(__DIR__ . '/src'),
        realpath(__DIR__ . '/htdocs'),
        realpath(__DIR__ . '/httpdocs'),
        realpath(__DIR__ . '/httpsdocs'),
        realpath(__DIR__ . '/public_html'),
        realpath(__DIR__ . '/wwww'),
        realpath(__DIR__ . '/wwwwroot'),
    ]));

/*
 * @author   [config] Tell Konkle <tellkonkle@gmail.com>
 * @version  2021-10-12 14:49:27 - PHP CS Fixer 3.1.0 River
 * @link     https://github.com/FriendsOfPHP/PHP-CS-Fixer
 */
return (new PhpCsFixer\Config())
    ->setFinder($finder)
    ->setRiskyAllowed(TRUE)
    ->setUsingCache(FALSE)
    ->setRules([
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'binary_operator_spaces' => [
            'operators' => [
                '='  => 'align',
                '=>' => 'align',
            ],
        ],
        'concat_space' => [
            'spacing' => 'one',
        ],
        'constant_case' => [
            'case' => 'upper',
        ],
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
        ],
        'single_class_element_per_statement' => [
            'elements' => [
                'property',
            ],
        ],
        'visibility_required' => [
            'elements' => [
                'property',
                'method',
            ],
        ],
        'cast_spaces'                           => TRUE,
        'combine_consecutive_unsets'            => TRUE,
        'blank_line_after_namespace'            => TRUE,
        'blank_line_before_statement'           => FALSE,
        'braces'                                => FALSE,
        'class_definition'                      => TRUE,
        'elseif'                                => TRUE,
        'encoding'                              => TRUE,
        'full_opening_tag'                      => TRUE,
        'function_declaration'                  => TRUE,
        'indentation_type'                      => TRUE,
        'linebreak_after_opening_tag'           => FALSE,
        'line_ending'                           => TRUE,
        'lowercase_cast'                        => TRUE,
        'lowercase_keywords'                    => TRUE,
        'no_blank_lines_after_class_opening'    => TRUE,
        'no_blank_lines_after_phpdoc'           => TRUE,
        'no_break_comment'                      => FALSE,
        'no_closing_tag'                        => TRUE,
        'no_extra_blank_lines'                  => TRUE,
        'no_spaces_after_function_name'         => TRUE,
        'no_spaces_around_offset'               => TRUE,
        'no_spaces_inside_parenthesis'          => FALSE,
        'no_trailing_comma_in_singleline_array' => TRUE,
        'no_trailing_whitespace'                => TRUE,
        'no_trailing_whitespace_in_comment'     => TRUE,
        'no_unused_imports'                     => TRUE,
        'no_useless_else'                       => TRUE,
        'no_useless_return'                     => TRUE,
        'no_whitespace_before_comma_in_array'   => TRUE,
        'no_whitespace_in_blank_line'           => TRUE,
        'normalize_index_brace'                 => TRUE,
        'phpdoc_indent'                         => TRUE,
        'phpdoc_to_comment'                     => TRUE,
        'phpdoc_trim'                           => TRUE,
        'single_blank_line_at_eof'              => TRUE,
        'switch_case_semicolon_to_colon'        => TRUE,
        'switch_case_space'                     => TRUE,
        'single_line_after_imports'             => TRUE,
        'single_import_per_statement'           => TRUE,
        'single_quote'                          => TRUE,
        'ternary_to_null_coalescing'            => TRUE,
        'trailing_comma_in_multiline'           => TRUE,
        'trim_array_spaces'                     => TRUE,
    ]);
