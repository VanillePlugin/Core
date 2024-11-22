<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

use VanillePlugin\inc\Stringify;
use PHPUnit\Framework\TestCase;

final class StringifyTest extends TestCase
{
    public function testReplace()
    {
        $string  = 'test1';
        $search  = '1';
        $replace = '2';
        $this->assertSame('test2', Stringify::replace($search, $replace, $string, $count));
        $this->assertSame(['test2'], Stringify::replace([$search], [$replace], [$string]));
        $this->assertEquals(1, $count);
    }

    public function testSubReplace()
    {
        $string  = 'test';
        $replace = 's';
        $this->assertSame('tests', Stringify::subReplace($string, $replace, 4));
        $this->assertSame(['tests'], Stringify::subReplace([$string], [$replace], [4]));
    }

    public function testReplaceArray()
    {
        $replace = [
            '{test}'    => 'test',
            '{example}' => 'example'
        ];
        $subject = '{test} {example}';
        $this->assertSame('test example', Stringify::replaceArray($replace, $subject));
    }

    public function testReplaceRegex()
    {
        $replace = 'number';
        $subject = 'Test 123';
        $this->assertEquals('Test number', Stringify::replaceRegex('/\d+/', $replace, $subject));
    }

    public function testRemove()
    {
        $this->assertEquals('test', Stringify::remove('this', 'thistest'));
    }

    public function testSubRemove()
    {
        $this->assertSame('test', Stringify::subRemove('tests', 4));
    }

    public function testRemoveRegex()
    {
        $this->assertEquals('test', Stringify::removeRegex('/\d+/', 'test123'));
    }

    public function testRepeat()
    {
        $this->assertEquals('test, test, ', Stringify::repeat('test, ', 2));
    }

    public function testLowercase()
    {
        $this->assertEquals('test example', Stringify::lowercase('Test Example'));
    }

    public function testUppercase()
    {
        $this->assertEquals('TEST EXAMPLE', Stringify::uppercase('test example'));
    }

    public function testCapitalize()
    {
        $this->assertEquals('Test example', Stringify::capitalize('test Example'));
    }

    public function testSlugify()
    {
        $this->assertEquals('test-example-1', Stringify::slugify('Test Example - 1'));
        $this->assertEquals('test-example-1', Stringify::slugify('Test Example_1'));
    }

    public function testContains()
    {
        $this->assertTrue(Stringify::contains('test example', 'test'));
    }

    public function testSplit()
    {
        $this->assertEquals(['test', 'text'], Stringify::split('testtext', ['length' => 4]));
        $args = [
			'regex' => '/([\s\n\r]+)/u',
			'limit' => 0,
			'flags' => 2
		];
        $this->assertEquals(['test', ' ', 'text'], Stringify::split('test text', $args));
    }

    public function testIsUtf8()
    {
        $this->assertTrue(Stringify::isUtf8('Test ABC'));
    }

    public function testFormatPath()
    {
        $this->assertEquals('test/example/path', Stringify::formatPath('test\example\path'));
    }

    public function testFormatSpace()
    {
        $this->assertEquals('test example path', Stringify::formatSpace('test  example  path'));
    }

    public function testFormatKey()
    {
        $this->assertEquals('testexamplekey', Stringify::formatKey('test  example  key'));
    }

    public function testUnSlash()
    {
        $string = 'Example with \backslash';
        $result = 'Example with backslash';
        $this->assertEquals($result, Stringify::unSlash($string));

        $string = 'Example with \\\ backslashs';
        $result = 'Example with \ backslashs';
        $this->assertEquals($result, Stringify::unSlash($string));
    }

    public function testSlash()
    {
        $string = "This test's";
        $result = "This test\'s";
        $this->assertEquals($result, Stringify::slash($string));

        $string = 'This test"s"';
        $result = 'This test\"s\"';
        $this->assertEquals($result, Stringify::slash($string));
    }

    public function testUntrailingSlash()
    {
        $string = 'http://example.com/';
        $result = 'http://example.com';
        $this->assertEquals($result, Stringify::untrailingSlash($string));
    }

    public function testTrailingSlash()
    {
        $string = 'http://example.com';
        $result = 'http://example.com/';
        $this->assertEquals($result, Stringify::trailingSlash($string));
    }

    public function testStripSlash()
    {
        $string = "This test\'s example\'s";
        $result = "This test's example's";
        $this->assertEquals($result, Stringify::stripSlash($string));

        $string = 'This \"test\" \"example\"';
        $result = 'This "test" "example"';
        $this->assertEquals($result, Stringify::stripSlash($string));

        $string = 'This \"test\" \\double backslash';
        $result = 'This "test" double backslash';
        $this->assertEquals($result, Stringify::stripSlash($string));

        $string = "This test\'s example\'s json {\"test\":\"example\'s\"}";
        $result = 'This test\'s example\'s json {"test":"example\'s"}';
        $this->assertEquals($result, Stringify::stripSlash($string));
    }

    public function testDeepStripSlash()
    {
        $string = "This test\'s example\'s json {\"test\":\"example\'s\"}";
        $result = 'This test\'s example\'s json {"test":"example\'s"}';
        $this->assertEquals($result, Stringify::deepStripSlash($string));

        $string = "This test\'s example\'s json {\"test\":\"example\'s\"}";
        $result = 'This test\'s example\'s json {"test":"example\'s"}';
        $this->assertEquals(['test' => $result], Stringify::deepStripSlash(['test' => $string]));
    }

    public function testStripTag()
    {
        $string = '<p>This is <strong>an</strong> example <em>with</em> tags</p>';
        $result = 'This is an example with tags';
        $this->assertEquals($result, Stringify::stripTag($string));

        $string = '<p>This is <br> a paragraph<br> with break tags</p>';
        $result = 'This is a paragraph with break tags';
        $this->assertEquals($result, Stringify::stripTag($string, true));
    }

    public function testStripNumber()
    {
        $this->assertEquals('test', Stringify::stripNumber('test123'));
    }

    public function testStripChar()
    {
        $this->assertEquals('testexample', Stringify::stripChar('test@example'));
    }

    public function testStripSpace()
    {
        $this->assertEquals('testexample', Stringify::stripSpace('test example'));
    }

    public function testStripBreak()
    {
        $this->assertEquals('test example', Stringify::stripBreak("test \nexample"));
    }

    public function testUnShortcode()
    {
        $string = 'This is some [shortcode] content.';
        $result = 'This is some  content.';
        $this->assertEquals($result, Stringify::unShortcode($string));
    }

    public function testUnserialize()
    {
        $this->assertEquals(['test' => '123'], Stringify::unserialize('a:1:{s:4:"test";s:3:"123";}'));
    }

    public function testSerialize()
    {
        $this->assertEquals('a:1:{s:4:"test";s:3:"123";}', Stringify::serialize(['test' => '123']));
    }

    public function testIsSerialized()
    {
        $this->assertTrue(Stringify::isSerialized('a:1:{s:4:"test";s:3:"123";}'));
        $this->assertFalse(Stringify::isSerialized('{"test":"123"}'));
    }

    public function testEscapeUrl()
    {
        if ( !has_filter('clean_url') ) {
            $this->assertEquals('', Stringify::escapeUrl('https://example.com', ['http']));
            $this->assertEquals('', Stringify::escapeUrl('http://example.com', ['https']));
            $this->assertEquals('', Stringify::escapeUrl('test://example.com'));
        
            $this->assertEquals('http://example.com', Stringify::escapeUrl('example.com'));
            $this->assertEquals('http://example.com', Stringify::escapeUrl('http://example.com'));
            $this->assertEquals('https://example.com', Stringify::escapeUrl('https://example.com'));
            $this->assertEquals('test://example.com', Stringify::escapeUrl('test://example.com', ['test']));

        } else {
            $this->assertTrue(true);
        }
    }

    public function testEscapeHTML()
    {
        if ( !has_filter('esc_html') ) {
            $string = '<p>This is <strong>HTML</strong> content</p>';
            $result = '&lt;p&gt;This is &lt;strong&gt;HTML&lt;/strong&gt; content&lt;/p&gt;';
            $this->assertEquals($result, Stringify::escapeHTML($string));

        } else {
            $this->assertTrue(true);
        }
    }

    public function testEscapeXML()
    {
        if ( !has_filter('esc_xml') ) {
            $string = '<tag attribute="value">This is some content</tag>';
            $result = "&lt;tag attribute=&quot;value&quot;&gt;This is some content&lt;/tag&gt;";
            $this->assertEquals($result, Stringify::escapeXML($string));

        } else {
            $this->assertTrue(true);
        }
    }

    public function testEscapeJS()
    {
        if ( !has_filter('js_escape') ) {
            $string = 'This is a "quoted" string with \'single quotes\'';
            $result = "This is a &quot;quoted&quot; string with \'single quotes\'";
            $this->assertEquals($result, Stringify::escapeJS($string));

        } else {
            $this->assertTrue(true);
        }
    }

    public function testEscapeSQL()
    {
        $string = "SELECT * FROM `test` WHERE username='admin';";
        $result = "SELECT * FROM `test` WHERE username=\'admin\';";
        $this->assertEquals($result, Stringify::escapeSQL($string));
    }

    public function testEscapeAttr()
    {
        if ( !has_filter('attribute_escape') ) {
            $string = 'This is a "quoted" string with \'single quotes\'';
            $result = "This is a &quot;quoted&quot; string with &#039;single quotes&#039;";
            $this->assertEquals($result, Stringify::escapeAttr($string));

        } else {
            $this->assertTrue(true);
        }
    }

    public function testEscapeTextarea()
    {
        if ( !has_filter('esc_textarea') ) {
            $string = '<p>This is <strong>HTML</strong> content</p>';
            $result = '&lt;p&gt;This is &lt;strong&gt;HTML&lt;/strong&gt; content&lt;/p&gt;';
            $this->assertEquals($result, Stringify::escapeTextarea($string));

        } else {
            $this->assertTrue(true);
        }
    }

    public function testSanitizeText()
    {
        if ( !has_filter('sanitize_text_field') ) {
            $string = "<p>This is <strong>HTML</strong> \ncontent</p>";
            $result = 'This is HTML content';
            $this->assertEquals($result, Stringify::sanitizeText($string));

        } else {
            $this->assertTrue(true);
        }
    }

    public function testSanitizeTextarea()
    {
        if ( !has_filter('sanitize_textarea_field') ) {
            $string = "<p>This is <strong>HTML</strong> \ncontent</p>";
            $result = "This is HTML \ncontent";
            $this->assertEquals($result, Stringify::sanitizeTextarea($string));

        } else {
            $this->assertTrue(true);
        }
    }

    public function testSanitizeTitle()
    {
        if ( !has_filter('sanitize_title') ) {
            $string = 'This is a Title with 123 Numbers and Special-Chars!';
            $result = 'this-is-a-title-with-123-numbers-and-special-chars';
            $this->assertEquals($result, Stringify::sanitizeTitle($string));

        } else {
            $this->assertTrue(true);
        }
    }

    public function testSanitizeKey()
    {
        if ( !has_filter('sanitize_key') ) {
            $this->assertEquals('testexamplekey', Stringify::sanitizeKey('test  example  key'));

        } else {
            $this->assertTrue(true);
        }
    }

    public function testSanitizeEmail()
    {
        if ( !has_filter('sanitize_email') ) {
            $this->assertEquals('conact@example.com', Stringify::sanitizeEmail('conact@ example.com'));

        } else {
            $this->assertTrue(true);
        }
    }

    public function testSanitizeColor()
    {
        $this->assertEquals('#ffffff', Stringify::sanitizeColor('#ffffff'));
        $this->assertEquals('#fff', Stringify::sanitizeColor('#fff'));
        $this->assertEquals('', Stringify::sanitizeColor('#ff'));
        $this->assertEquals('', Stringify::sanitizeColor('ffffff'));
    }

    public function testSanitizeHtmlClass()
    {
        if ( !has_filter('sanitize_html_class') ) {
            $this->assertEquals('test', Stringify::sanitizeHtmlClass('test'));
            $this->assertEquals('1test', Stringify::sanitizeHtmlClass('1test'));
            $this->assertEquals('test', Stringify::sanitizeHtmlClass('@test'));

        } else {
            $this->assertTrue(true);
        }
    }

    public function testSanitizeFilename()
    {
        if ( !has_filter('sanitize_file_name') 
          && !has_filter('sanitize_file_name_chars') ) {
            $this->assertEquals('file-name.txt', Stringify::sanitizeFilename('file name.txt'));

        } else {
            $this->assertTrue(true);
        }
    }

    public function testSanitizeMimeType()
    {
        if ( !has_filter('sanitize_mime_type') ) {
            $this->assertEquals('filename.txt', Stringify::sanitizeMimeType('filename .txt'));

        } else {
            $this->assertTrue(true);
        }
    }

    public function testSanitizeSqlOrder()
    {
        $string = "test DECS";
        $this->assertEquals('', Stringify::sanitizeSqlOrder($string));
        
        $string = "name ASC";
        $this->assertEquals($string, Stringify::sanitizeSqlOrder($string));
    }

    public function testSanitizeOption()
    {
        $key = 'new_admin_email';
        if ( !has_filter("sanitize_option_{$key}") ) {
            $value  = 'conact @ example.com';
            $result = 'conact@example.com';
            $this->assertEquals($result, Stringify::sanitizeOption($key, $value));

        } else {
            $this->assertTrue(true);
        }
    }

    public function testSanitizeMeta()
    {
        $type = 'post';
        $key  = 'admin-email';
        if ( !has_filter("sanitize_{$type}_meta_{$key}") ) {
            $value = 'conact @ example.com';
            $this->assertEquals($value, Stringify::sanitizeMeta($key, $value, $type));

        } else {
            $this->assertTrue(true);
        }
    }

    public function testSanitizeUser()
    {
        if ( !has_filter('sanitize_user') ) {
            $this->assertEquals('@Admin', Stringify::sanitizeUser('@$Admin$'));

        } else {
            $this->assertTrue(true);
        }
    }

    public function testSanitizeUrl()
    {
        if ( !has_filter('clean_url') ) {
            $this->assertEquals('', Stringify::sanitizeUrl('https://example.com', ['http']));
            $this->assertEquals('', Stringify::sanitizeUrl('http://example.com', ['https']));
            $this->assertEquals('', Stringify::sanitizeUrl('test://example.com'));
        
            $this->assertEquals('http://example.com', Stringify::sanitizeUrl('example.com'));
            $this->assertEquals('http://example.com', Stringify::sanitizeUrl('http://example.com'));
            $this->assertEquals('https://example.com', Stringify::sanitizeUrl('https://example.com'));
            $this->assertEquals('test://example.com', Stringify::sanitizeUrl('test://example.com', ['test']));
                
        } else {
            $this->assertTrue(true);
        }
    }

    public function testSanitizeHTML()
    {
        if ( !has_filter('wp_kses_allowed_html') ) {
            $value  = '<strong>Value</strong> with <script>alert("XSS")</script>';
            $result = '<strong>Value</strong> with alert("XSS")';
            $this->assertEquals($result, Stringify::sanitizeHTML($value));
                
        } else {
            $this->assertTrue(true);
        }
    }

    public function testMatch()
    {
        $this->assertEquals('123', Stringify::match('/[0-9]+/', 'Test 123 456'));
    }

    public function testMatchAll()
    {
        $this->assertEquals(['123', '456'], Stringify::matchAll('/[0-9]+/', 'Test 123 456'));
    }

    public function testShuffle()
    {
        $string = 'abcdef';
        $result = Stringify::shuffle($string);
        $this->assertEquals(strlen($string), strlen($result));
        $this->assertEquals(Stringify::count($string, 1), Stringify::count($result, 1));
    }

    public function testCount()
    {
        $string = 'abcdef';
        $result = Stringify::count($string, 1);
        $this->assertEquals(strlen($string), count($result));
    }

    public function testLimit()
    {
        $this->assertEquals('Example limit', Stringify::limit('Example limit test', 15));
    }

    public function testFilter()
    {
        $this->assertEquals('conact@example.com', Stringify::filter('conact @ example.com', 'email'));
    }

    public function testParse()
    {
        $string = 'name=John&age=30&city=New+York';
        $result = ['name' => 'John', 'age' => '30', 'city' => 'New York'];
        $this->assertEquals($result, Stringify::parse($string));
    }

    public function testParseUrl()
    {
        $string = 'https://example.com/path/to/resource?query=value#fragment';
        $result = [
            'scheme'   => 'https',
            'host'     => 'example.com',
            'path'     => '/path/to/resource',
            'query'    => 'query=value',
            'fragment' => 'fragment'
        ];
        $this->assertEquals($result, Stringify::parseUrl($string));
    }

    public function testBuildQuery()
    {
        $args = ['name' => 'test', 'number' => 1];
        $this->assertEquals('name=test&number=1', Stringify::buildQuery($args));
    }
}
