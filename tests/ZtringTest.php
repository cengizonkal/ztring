<?php

/**
 * @method assertEquals(string $string, \Conkal\Ztring $ensureRight)
 * @method assertTrue(bool $endsWith)
 * @method assertFalse(bool $endsWith)
 */
class ZtringTest extends PHPUnit_Framework_TestCase
{

    public function testRandomString()
    {
        $str = \Conkal\Ztring::random();
        $this->assertNotEmpty($str);
    }

    public function testHelperFunction()
    {
        $g = ztring("Test");
        $this->assertTrue(true);
    }

    public function testFirstXChars()
    {
        $this->assertEquals("T", ztring("Test")->firstChar());
        $this->assertEquals("Te", ztring("Test")->first2Chars());
        $this->assertEquals("Test", ztring("Test")->first20Chars());
        $this->assertEquals("Test", ztring("Test")->first30Chars());
        $this->assertEquals("", ztring("")->first30Chars());


    }

    public function testLastXChars()
    {
        $this->assertEquals("est", ztring("Test")->last3Chars());
        $this->assertEquals("t", ztring("Test")->lastChar());
        $this->assertEquals("", ztring("")->lastChar());
        $this->assertEquals("", ztring("")->last2Chars());
    }

    public function testFirstWord()
    {
        $this->assertEquals("This", ztring('This is a test')->firstWord());
    }

    public function testLastWord()
    {
        $this->assertEquals("test", ztring('This is a test')->lastWord());
        $this->assertEquals("test", ztring('This is a,test')->lastWord());
        $this->assertEquals("a1test", ztring('This is a1test')->lastWord());
        $this->assertEquals("", ztring('')->lastWord());
    }

    public function testLastWords()
    {
        $this->assertEquals("a test", ztring('This is a test.')->last2Words());
        $this->assertEquals("", ztring('')->last2Words());
        $this->assertEquals("This is a test", ztring('This is a test.')->last6Words());
    }

    public function testFirstWords()
    {
        $this->assertEquals("This is", ztring('This is a test')->first2Words());
        $this->assertEquals("This", ztring('This is a test')->first1Words());
        $this->assertEquals("This is a test", ztring('This is a test')->first20Words());
        $this->assertEquals("", ztring('')->first20Words());
    }

    public function testAcronym()
    {
        $this->assertEquals("TIAT", ztring('This is a test')->acronym());
        $this->assertEquals("T.I.A.T", ztring('This is a test')->acronym("."));
        $this->assertEquals("", ztring('')->acronym("."));
        $this->assertEquals("T", ztring('Test')->acronym("."));
    }


    public function testUppercase()
    {
        $this->assertEquals("T.I.A.T", ztring('This is a test')->acronym(".")->uppercase());
    }

    public function testSanitize()
    {
        $this->assertEquals('This is a test', ztring('This is a test!?=_Ğ\'ŞÇÖ')->sanitize());
    }

    public function testSlug()
    {
        $this->assertEquals('this_is_a_test', ztring('This is a testğüşçö')->slug('_'));
        $this->assertEquals('this-is-a-test', ztring('This is a testğüşçö')->slug('-'));
    }


    public function testCamelCase()
    {
        $this->assertEquals('thisIsATest', ztring('This is a test')->camelcase());
        $this->assertEquals('test', ztring('Test')->camelcase());
        $this->assertEquals('est', ztring('est')->camelcase());
        $this->assertEquals('bazıTürkçeKarakterler', ztring('bazı türkçe karakterler')->camelcase());
        $this->assertEquals('camelCase', ztring('Camel-Case')->camelcase());
    }

    public function testReplace()
    {
        $this->assertEquals('this is b test', ztring('this is a test')->replace('a', 'b'));
        $this->assertEquals('These are a test', ztring('This is a test')->replace(['This' => 'These', 'is' => 'are']));
    }

    public function testAscii()
    {
        $ascii = [
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
        $this->assertEquals('ocsigu', ztring('öçşiğü')->ascii($ascii));
        $this->assertEquals('OCSIGU', ztring('ÖÇŞİĞÜ')->ascii($ascii));
    }

    public function testAppend()
    {
        $this->assertEquals('A test, this is.', ztring('A test,')->append(' this is.'));
    }

    public function testPrepend()
    {
        $this->assertEquals('This is a test.', ztring('a test.')->prepend('This is '));
    }

    public function testCollapseWhiteSpace()
    {
        $this->assertEquals('This is a test.', ztring('This           is a test. ')->collapseWhitespace());
    }

    public function testSwapCase()
    {
        $this->assertEquals('This is a test.', ztring('tHIS IS A TEST.')->swapCase());
    }

    public function testStaticCreate()
    {
        $this->assertEquals('CENGIZ', \Conkal\Ztring::create('cengiz')->uppercase());
    }

    public function testAt()
    {
        $this->assertEquals('T', ztring('This is a test.')->at(0));
        $this->assertEquals('h', ztring('This is a test.')->at(1));
        $this->assertEquals('i', ztring('This is a test.')->at(2));
        $this->assertEquals('s', ztring('This is a test.')->at(3));
        $this->assertEquals(' ', ztring('This is a test.')->at(4));
        $this->assertEquals('.', ztring('This is a test.')->at(-1));
    }

    public function testEndsWith()
    {
        $this->assertTrue(ztring('this is a test')->endsWith('test'));
        $this->assertFalse(ztring('this is a TEST')->endsWith('test'));
        $this->assertTrue(ztring('this is a TEST')->endsWith('test', false));
    }

    public function testStartsWith()
    {
        $this->assertTrue(ztring('this is a test')->startsWith('this'));
        $this->assertFalse(ztring('this is a TEST')->startsWith('test'));
        $this->assertTrue(ztring('this is a TEST')->startsWith('THIS', false));
    }

    public function testEnsureLeft()
    {
        $this->assertEquals('http://foobar', ztring('foobar')->ensureLeft('http://'));
        $this->assertEquals('http://foobar', ztring('http://foobar')->ensureLeft('http://'));
    }

    public function testEnsureRight()
    {
        $this->assertEquals('foobar.com', ztring('foobar')->ensureRight('.com'));
        $this->assertEquals('foobar.com', ztring('foobar.com')->ensureRight('.com'));
    }

    public function testTitleCase()
    {
        $this->assertEquals('Title Case', ztring('title case')->titleCase());
        $this->assertEquals('Title Case', ztring('tItle cAse')->titleCase());
    }

    public function testReverse()
    {
        $this->assertEquals('Test', ztring('tseT')->reverse());
    }

    public function testSubstr()
    {
        $this->assertEquals('This', ztring('This is a test')->substr(0, 4));
    }

    public function testLowercase()
    {
        $this->assertEquals('this is a test', ztring('THIS IS A test')->lowerCase());
    }

    public function testRemoveWhiteSpace()
    {
        $this->assertEquals('Thisisatest', ztring('This is a     test     ')->removeWhiteSpace());
    }

    public function testToBoolean()
    {
        $this->assertTrue(ztring('true')->toBoolean());
        $this->assertTrue(ztring('1')->toBoolean());
        $this->assertTrue(ztring('on')->toBoolean());
        $this->assertFalse(ztring('off')->toBoolean());
        $this->assertFalse(ztring('0')->toBoolean());
        $this->assertFalse(ztring('false')->toBoolean());

    }

    public function testUpperCaseFirst()
    {
        $this->assertEquals('This is a test', ztring('this is a test')->upperCaseFirst());
    }

    public function testLowerCaseFirst()
    {
        $this->assertEquals('this', ztring('This')->lowerCaseFirst());
    }


    public function testNumber()
    {
        $this->assertEquals('123', ztring('1---*?=)(2hjkghkj,iüğ\ş3/*-')->number());
    }
}
