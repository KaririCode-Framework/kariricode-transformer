<?php

declare(strict_types=1);

/**
 * KaririCode Devkit project-level overrides.
 *
 * Exclude test files from cs-fixer to prevent @PHP84Migration from transforming
 * `(new Foo())->method()` → `new Foo()->method()` — that PHP 8.4 syntax prevents
 * pcov from correctly attributing code-coverage to the tested class.
 */
return [
    'cs_fixer_finder_exclude_dirs' => ['tests'],
];
