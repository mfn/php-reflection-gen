# PHP-Analzyer [ ![Travis Build Status](https://travis-ci.org/mfn/php-reflection-gen.svg?branch=master)](https://travis-ci.org/mfn/php-reflection-gen)

Homepage: https://github.com/mfn/php-reflection-gen

# Blurb

Uses the reflection capabilities of the current PHP runtime and emits all
classes, interfaces, traits and functions in parsable (but probably not runnable)
PHP code.

# Install / Usage

```
composer require mfn/php-reflection-gen 0.0.1
```

`php_reflection_gen.php`

Will output all reflected data to stdout. Optionally you can write it to a file
or to a directory.

In case using `--directory`, for each PHP extension a separate file is created.

# TODOs / Ideas
- Properly handle namespaces
  (currently not a concern because of no PHP internals use Namespaces)
- Handle non-internal PHP reflections
- Support older (<5.4) PHP versions

© Markus Fischer <markus@fischer.name>
