<?php

namespace Conkal;


use Exception;
use Traversable;

class Ztring implements \IteratorAggregate, \ArrayAccess, \Countable
{

    private $string = "";


    public function __construct($string)
    {
        $this->string = $string;
    }


    public static function create($string)
    {
        return new static($string);
    }

    public function firstChar()
    {
        return new static($this->string[0]);
    }

    public function lastChar()
    {
        return new static(substr($this->string, -1));
    }

    public function __call($method, $arguments)
    {
        if (preg_match('/(first)(\w*)(Chars)/', $method, $parameters)) {
            return $this->firstXChars($parameters[2]);
        }
        if (preg_match('/(last)(\w*)(Chars)/', $method, $parameters)) {
            return $this->lastXChars($parameters[2]);
        }
        if (preg_match('/(last)(\w*)(Words)/', $method, $parameters)) {
            return $this->lastXWords($parameters[2]);
        }
        if (preg_match('/(first)(\w*)(Words)/', $method, $parameters)) {
            return $this->firstXWords($parameters[2]);
        }

    }

    public function firstWord()
    {
        preg_match('/^\w+/', $this->string, $result);
        return new static(isset($result[0]) ? $result[0] : '');
    }

    public function firstXWords($n)
    {
        $result = $this->words();
        $n = min(count($result), $n);
        $words = [];
        for ($i = 0; $i < $n; $i++) {
            array_push($words, $result[$i]);
        }
        return new static(implode(' ', $words));
    }

    public function lastWord()
    {
        preg_match('/\w+$/', $this->string, $result);
        return new static(isset($result[0]) ? $result[0] : '');
    }

    public function lastXWords($n)
    {

        $result = $this->words();
        $max = min(count($result), $n);
        $words = [];
        for ($i = count($result) - $max; $i < count($result); $i++) {
            array_push($words, $result[$i]);
        }
        return new static(implode(' ', $words));
    }

    public function firstXChars($n)
    {
        return new static(substr($this->string, 0, $n));
    }

    public function lastXChars($n)
    {
        return new static(substr($this->string, ($n * -1)));
    }


    public static function random($length = 16)
    {
        $string = '';
        while (($len = strlen($string)) < $length) {
            $size = $length - $len;
            $bytes = static::randomBytes();
            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }
        return $string;
    }


    private static function randomBytes($length = 16)
    {
        if (function_exists('random_bytes')) {
            $bytes = random_bytes($length);
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length, $strong);
            if ($bytes === false || $strong === false) {
                throw new RuntimeException('Unable to generate random string.');
            }
        } else {
            throw new RuntimeException('OpenSSL extension is required for PHP 5 users.');
        }
        return $bytes;
    }

    public function __toString()
    {
        if (!$this->string) {
            return "";
        }
        return $this->string;
    }

    public function acronym($glue = "")
    {
        $acronym = [];
        foreach ($this->words() as $word) {
            array_push($acronym, mb_strtoupper($word[0]));
        }
        return new static(implode($glue, $acronym));
    }

    public function words()
    {
        preg_match_all('/\w+/', $this->string, $result);
        return $result[0];
    }

    public function uppercase()
    {
        return new static(mb_strtoupper($this->string));
    }

    public function sanitize()
    {
        return new static(preg_replace('/[^a-zA-Z0-9 .]/i', '', $this->string));
    }

    public function slug($separator = '-')
    {
        $title = $this->sanitize();
        $flip = $separator == '-' ? '_' : '-';
        $title = preg_replace('![' . preg_quote($flip) . ']+!u', $separator, $title);
        $title = preg_replace('![^' . preg_quote($separator) . '\pL\pN\s]+!u', '', mb_strtolower($title));
        $title = preg_replace('![' . preg_quote($separator) . '\s]+!u', $separator, $title);
        return new static(trim($title, $separator));
    }

    public function camelcase()
    {
        $string = $this->lowerCaseFirst();
        $string = preg_replace('/^[-_]+/', '', $string);
        $string = preg_replace_callback('/[-_\s]+(.)?/u', function ($match) {
            if (isset($match[1])) {
                return \mb_strtoupper($match[1]);
            }
            return '';
        }, $string);

        $string = preg_replace_callback('/[\d]+(.)?/u', function ($match) {
            return \mb_strtoupper($match[0]);
        }, $string);
        return new static($string);
    }

    public function lowerCaseFirst()
    {
        $first = \mb_substr($this->string, 0, 1);
        $rest = \mb_substr($this->string, 1, $this->length() - 1);
        $str = \mb_strtolower($first) . $rest;
        return new static($str);
    }

    public function upperCaseFirst()
    {
        $first = \mb_substr($this->string, 0, 1);
        $rest = \mb_substr($this->string, 1, $this->length() - 1);
        $str = \mb_strtoupper($first) . $rest;
        return new static($str);
    }

    public function length()
    {
        return mb_strlen($this->string);
    }

    public function count()
    {
        return $this->length();
    }

    public function replace($search, $replace = null)
    {
        if (is_array($search)) {
            foreach ($search as $key => $value) {
                $this->string = preg_replace('/' . preg_quote($key) . '/', $value, $this->string);
            }
            return new static($this->string);
        } else {
            return new static(preg_replace('/' . preg_quote($search) . '/', $replace, $this->string));
        }
    }

    public function append($str)
    {
        return new static($this . $str);
    }

    public function prepend($str)
    {
        return new static($str . $this);
    }

    public function ascii(array $languageSpecific = null)
    {
        $str = $this->replace($languageSpecific);
        $str = preg_replace('/[^\x20-\x7E]/u', '', $str);
        return new static($str);
    }

    public function collapseWhitespace()
    {
        return (new static(trim(preg_replace('/ {2,}/', ' ', $this))))->trim();
    }

    public function trim($char = ' ')
    {
        return new static(preg_replace('/^[' . preg_quote($char) . ']+|[' . preg_quote($char) . ']+$/', '', $this));

    }

    public function swapCase()
    {
        $str = preg_replace_callback('/[\S]/u', function ($match) {
            if ($match[0] == \mb_strtoupper($match[0])) {
                return \mb_strtolower($match[0]);
            }
            return \mb_strtoupper($match[0]);
        }, $this->string);
        return new static($str);
    }


    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->chars());
    }


    public function substr($start, $length = null)
    {
        $length = $length === null ? $this->length() : $length;
        $str = \mb_substr($this->string, $start, $length);
        return new static($str);
    }

    public function at($index)
    {
        return $this->substr($index, 1);
    }

    public function chars()
    {
        $chars = [];
        for ($i = 0, $l = $this->length(); $i < $l; $i++) {
            $chars[] = $this->at($i)->str;
        }
        return $chars;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        $length = $this->length();
        $offset = (int)$offset;
        if ($offset >= 0) {
            return ($length > $offset);
        }
        return ($length >= abs($offset));
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        $offset = (int)$offset;
        $length = $this->length();
        if (($offset >= 0 && $length <= $offset) || $length < abs($offset)) {
            throw new \OutOfBoundsException('No character exists at the index');
        }
        return \mb_substr($this->string, $offset, 1);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function offsetSet($offset, $value)
    {
        throw new Exception('Cannot modify char');
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function offsetUnset($offset)
    {
        throw new Exception('cannot unset char');
    }

    public function endsWith($string, $caseSensitive = true)
    {
        preg_match('/' . preg_quote($string, '/') . '$/' . ($caseSensitive ? '' : 'i'), $this->string, $result);
        return count($result) > 0;
    }

    public function startsWith($string, $caseSensitive = true)
    {
        preg_match('/^' . preg_quote($string, '/') . '/' . ($caseSensitive ? '' : 'i'), $this->string, $result);
        return count($result) > 0;
    }

    public function ensureLeft($string)
    {
        if (!$this->startsWith($string)) {
            return new static($string . $this);
        }
        return $this;
    }

    public function ensureRight($string)
    {
        if (!$this->endsWith($string)) {
            return new static($this . $string);
        }
        return $this;
    }

    public function titleCase()
    {
        return new static(\mb_convert_case($this->string, \MB_CASE_TITLE));
    }

    public function reverse()
    {
        $strLength = $this->length();
        $reversed = '';
        for ($i = $strLength - 1; $i >= 0; $i--) {
            $reversed .= \mb_substr($this->string, $i, 1);
        }
        return new static($reversed);
    }

    public function removeWhiteSpace()
    {
        return new static(preg_replace('/\s/', '', $this->string));
    }

    public function lowerCase()
    {
        return new static(mb_strtolower($this));
    }

    public function toBoolean()
    {
        $key = $this->lowerCase()->__toString();
        $map = [
            'true' => true,
            '1' => true,
            'on' => true,
            'yes' => true,
            'false' => false,
            '0' => false,
            'off' => false,
            'no' => false
        ];
        if (array_key_exists($key, $map)) {
            return $map[$key];
        }
        return false;
    }

    public function number()
    {
        return new static(preg_replace('/[^0-9]/', '', $this->string));
    }


}
