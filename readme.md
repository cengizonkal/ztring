# ztring
[![Latest Stable Version](https://poser.pugx.org/conkal/ztring/v/stable)](https://packagist.org/packages/conkal/ztring)
[![License](https://poser.pugx.org/conkal/ztring/license)](https://packagist.org/packages/conkal/ztring)
[![Total Downloads](https://poser.pugx.org/conkal/ztring/downloads)](https://packagist.org/packages/conkal/ztring)
![Build](https://github.com/cengizonkal/ztring/workflows/Build/badge.svg)

A PHP string manipulation library.

* [Installation](#installation)
* [OO and Chaining](#oo-and-chaining)
* [Class methods](#class-methods)
<table>
    <tr>
        <td><a href="#append">append</a></td>
        <td><a href="#prepend">prepend</a></td>
        <td><a href="#camelcase">camelcase</a></td>
        <td><a href="#count">count</a></td>
    </tr>
    <tr>
        <td><a href="#at">at</a></td>
        <td><a href="#chars">chars</a></td>
        <td><a href="#collapsewhitespace">collapseWhitespace</a></td>
        <td><a href="#endswith">endsWith</a></td>
    </tr>
    <tr>
        <td><a href="#ensureleft">ensureLeft</a></td>
        <td><a href="#ensureright">ensureRight</a></td>
        <td><a href="#firstchar">firstChar</a></td>
        <td><a href="#firstxchars">firstXChars</a></td>
    </tr>
    <tr>
            <td><a href="#lastchar">lastChar</a></td>
            <td><a href="#lastxchars">lastXChars</a></td>
            <td><a href="#length">length</a></td>
            <td><a href="#lowercasefirst">lowerCaseFirst</a></td>
    </tr>
    <tr>
           <td><a href="#prepend">prepend</a></td>
           <td><a href="#replace">replace</a></td>
           <td><a href="#reverse">reverse</a></td>
           <td><a href="#random">random</a></td>
    </tr>
    <tr>
           <td><a href="#slug">slug</a></td>
           <td><a href="#startswith">startsWith</a></td>
           <td><a href="#substr">substr</a></td>
           <td><a href="#swapcase">swapCase</a></td>
    </tr>
    <tr>
            <td><a href="#removewhitespace">removeWhiteSpace</a></td>
            <td><a href="#lowercase">lowerCase</a></td>
            <td><a href="#toboolean">toBoolean</a></td>
            <td><a href="#ascii">ascii</a></td>
    </tr>
    <tr>
        <td><a href="#trim">trim</a></td>
        <td><a href="#words">words</a></td>
        <td><a href="#sanitize">sanitize</a></td>
        <td><a href="#acronym">acronym</a></td>
    </tr>
    <tr>
        <td><a href="#number">number</a></td>
    </tr>
</table>

## Installation
```
composer require conkal/ztring
```

```php
use Conkal\ztring;
```
## OO and Chaining

The library offers OO method chaining, as seen below:

```php
use Conkal\ztring;
echo ztring('this is test')->collapseWhitespace()->swapCase(); // THIS IS TEST
```

`Conkal\ztring` has a __toString() method, which returns the current string
when the object is used in a string context, ie:
`(string) ztring::create('foo')  // 'foo'`

## Implemented Interfaces

`Conkal\ztring` implements the `IteratorAggregate` interface, meaning that
`foreach` can be used with an instance of the class:

```php
$string = \Conkal\ztring::create('test');
foreach ($string as $char) {
    echo $char;
}
// 'test'
```

It implements the `Countable` interface, enabling the use of `count()` to
retrieve the number of characters in the string:

```php
$string = \Conkal\ztring::create('test');
count($string);  // 3
```

Furthermore, the `ArrayAccess` interface has been implemented. As a result,
`isset()` can be used to check if a character at a specific index exists. And
since `Conkal\ztring` is immutable, any call to `offsetSet` or `offsetUnset`
will throw an exception. `offsetGet` has been implemented, however, and accepts
both positive and negative indexes. Invalid indexes result in an
`OutOfBoundsException`.

```php
$string = \Conkal\ztring::create('test');
echo $string[1];     // 't'
```
## Class methods

##### create(string $str)

Creates a ztring object and assigns string property.
$str is cast to a string prior to assignment.

```php
$string = \Conkal\ztring::create('test'); // 'test'
```

## Instance Methods
##### append(string $string)

Returns a new string with $string appended.

```php
ztring('This is a ')->append('test.'); // This is a test.
```

##### at(int $index)

Returns the character at $index, with indexes starting at 0.

```php
ztring('This is a test')->at(0); // 'T'
ztring('This is a test')->at(1); // 'h'
ztring('This is a test')->at(-1); // 't'
```

##### camelcase()

Returns a camelCase version of the string. Trims surrounding spaces,
capitalizes letters following digits, spaces, dashes and underscores,
and removes spaces, dashes, as well as underscores.

```php
ztring('Camel-Case')->camelcase(); // 'camelCase'
```

##### chars()

Returns an array consisting of the characters in the string.

```php
ztring('test')->chars(); // ['t','e','s','t']
```

##### collapseWhitespace()

Trims the string and replaces consecutive whitespace characters with a
single space. This includes tabs and newline characters, as well as
multibyte whitespace such as the thin space and ideographic space.

```php
ztring('This   is a               test')->collapseWhitespace(); // 'This is a test'
```

##### endsWith(string $substring [, boolean $caseSensitive = true ])

Returns true if the string ends with $substring, false otherwise. By
default, the comparison is case-sensitive, but can be made insensitive by
setting $caseSensitive to false.

```php
ztring('this is a test')->endsWith('test'); // true
```

##### ensureLeft(string $substring)

Ensures that the string begins with $substring. If it doesn't, it's prepended.

```php
ztring('foobar')->ensureLeft('http://'); // 'http://foobar'
```

##### ensureRight(string $substring)

Ensures that the string ends with $substring. If it doesn't, it's appended.

```php
ztring('foobar')->ensureRight('.com'); // 'foobar.com'
```

##### firstChar()

Returns the first $n characters of the string.

```php
ztring('test')->firstChar(); // 't'
```

##### firstXChars()

Returns the first $n characters of the string.

```php
ztring('test')->first3Chars(); // 'tes'
```

##### lastChar()

Returns the last $n characters of the string.

```php
ztring('test')->lastChar(); // 't'
```

##### lastXChars()

Returns the last $n characters of the string.

```php
ztring('test')->last3Chars(); // 'est'
```


##### length()

Returns the length of the string. An alias for PHP's mb_strlen() function.

```php
ztring('fòôbàř')->length(); // 6
```


##### lowerCaseFirst()

Converts the first character of the supplied string to lower case.

```php
ztring('Test')->lowerCaseFirst(); // 'test'
```
##### prepend(string $string)

Returns a new string starting with $string.

```php
ztring('bàř')->prepend('fòô'); // 'fòôbàř'
```

##### removeLeft(string $substring)

Returns a new string with the prefix $substring removed, if present.

##### replace(string $search, string $replacement)

Replaces all occurrences of $search in $str by $replacement.

```php
ztring('This is test.')->replace('This ', 'These '); // 'These is test.'
```

##### reverse()

Returns a reversed string. A multibyte version of strrev().

```php
ztring('fòôbàř')->reverse(); // 'řàbôòf'
```

##### random()

Create a random string

```php
\Conkal\ztring::random(); 
```

##### slug( [string $separator = '-'])

```php
ztring('This is a testğüşçö')->slug(); // 'this-is-a-test'
```

##### startsWith(string $substring [, boolean $caseSensitive = true ])

Returns true if the string begins with $substring, false otherwise.
By default, the comparison is case-sensitive, but can be made insensitive
by setting $caseSensitive to false.

```php
ztring('This is a test')->startsWith('This', false); // true
```

##### substr(int $start [, int $length ])

Returns the substring beginning at $start with the specified $length.
It differs from the mb_substr() function in that providing a $length of
null will return the rest of the string, rather than an empty string.

```php
ztring('This is a test')->substr(0, 4); // 'This'
```

##### swapCase()

Returns a case swapped version of the string.

```php
ztring('Test')->swapCase(); // 'tEST'
```

##### titleCase()

Returns a trimmed string with the first letter of each word capitalized.

```php
ztring('this is a test')->titleCase();
// 'This Is A Test'
```

##### ascii($languageSpecific)

Returns an ASCII version of the string.

```php
  $languageSpecific = [
            'ğ' => 'g',
            'ü' => 'u',
            'ı' => 'i',
            'ş' => 's',
            'ç' => 'c',
            'ö' => 'o',
            'Ğ' => 'G',
            'Ü' => 'U',
            'Ş' => 'S',
            'Ö' => 'O',
            'Ç' => 'C',
            'İ' => 'I',
        ];
ztring('türkçe')->ascii($languageSpecific); // 'turkce'
```

##### toBoolean()

Returns a boolean representation of the given logical string value.
For example, 'true', '1', 'on' and 'yes' will return true. 'false', '0',
'off', and 'no' will return false. In all instances, case is ignored.

```php
ztring('OFF')->toBoolean(); // false
```

##### toLowerCase()

Converts all characters in the string to lowercase. An alias for PHP's
mb_strtolower().

```php
ztring('THIS')->lowerCase(); // 'this'
```
##### toTitleCase()

Converts the first character of each word in the string to uppercase.

```php
ztring('this is a test')->titleCase(); // 'Fòô Bàř'
```

##### uppercase()

Converts all characters in the string to uppercase. An alias for PHP's
mb_strtoupper().

```php
ztring('test')->uppercase(); // 'TEST'
```

##### trim([, string $chars])

Returns a string with whitespace removed from the start and end of the
string. Supports the removal of unicode whitespace. Accepts an optional
string of characters to strip instead of the defaults.

```php
ztring('  test  ')->trim(); // 'test'
```

##### upperCaseFirst()

Converts the first character of the supplied string to upper case.

```php
ztring('this is a test')->upperCaseFirst(); // 'This is a test'
```

##### words()

Returns words as array

```php
ztring('this is a test')->words(); // ['This', 'is', 'a', 'test']
```
##### sanitize()

Removes all special characters

```php
ztring('this is a test?--*üğişçö')->sanitize(); // 'this is a test'
```

##### acronym()

Creates an acronym

```php
ztring('This is a test')->acronym(); // 'TIAT'
ztring('This is a test')->acronym('.'); // 'T.I.A.T.'
```

##### number()

return only digits between 0-9

```php
ztring('1---*?=)(2hjkghkj,iüğ\ş3/*-')->number(); //123
```

## Tests

From the project directory, tests can be ran using `phpunit`

## License

Released under the MIT License - see `LICENSE.txt` for details.