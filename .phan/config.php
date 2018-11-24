<?php
return [
    'directory_list' => [
        'src',
        'test',
        'vendor',
        'bin',
    ],
    'analyzed_file_extensions' => ['php'],
    'exclude_analysis_directory_list' => [
        'vendor/'
    ],
    'plugins' => [
        'DollarDollarPlugin',
        'UnreachableCodePlugin',
        'DuplicateArrayKeyPlugin',
        'PregRegexCheckerPlugin',
        'PrintfCheckerPlugin',
        'UnknownElementTypePlugin',
        'DuplicateExpressionPlugin',
        'HasPHPDocPlugin',
    ],
];
