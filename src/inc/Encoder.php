<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.0
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

/**
 * Built-in encoding class,
 * @see https://github.com/Jakiboy/Encoder
 */
class Encoder
{
    /**
     * @access protected
     * @var bool $useSpecial
     * @var bool $useIconv
     * @var mixed $iconvOption
     */
    protected $useSpecial = true;
    protected $useIconv = false;
    protected $iconvOption = false;

    /**
     * @access public
     * @var array WINDOWS1252, Windows-1252 (CP1252) to UTF-8
     */
    public const WINDOWS1252 = [
        128 => "\xe2\x82\xac",
        130 => "\xe2\x80\x9a",
        131 => "\xc6\x92",
        132 => "\xe2\x80\x9e",
        133 => "\xe2\x80\xa6",
        134 => "\xe2\x80\xa0",
        135 => "\xe2\x80\xa1",
        136 => "\xcb\x86",
        137 => "\xe2\x80\xb0",
        138 => "\xc5\xa0",
        139 => "\xe2\x80\xb9",
        140 => "\xc5\x92",
        142 => "\xc5\xbd",
        145 => "\xe2\x80\x98",
        146 => "\xe2\x80\x99",
        147 => "\xe2\x80\x9c",
        148 => "\xe2\x80\x9d",
        149 => "\xe2\x80\xa2",
        150 => "\xe2\x80\x93",
        151 => "\xe2\x80\x94",
        152 => "\xcb\x9c",
        153 => "\xe2\x84\xa2",
        154 => "\xc5\xa1",
        155 => "\xe2\x80\xba",
        156 => "\xc5\x93",
        158 => "\xc5\xbe",
        159 => "\xc5\xb8"
    ];

    /**
     * @access public
     * @var array UTF8, UTF-8 to Windows-1252
     */
    public const UTF8 = [
        "\xe2\x82\xac" => "\x80",
        "\xe2\x80\x9a" => "\x82",
        "\xc6\x92"     => "\x83",
        "\xe2\x80\x9e" => "\x84",
        "\xe2\x80\xa6" => "\x85",
        "\xe2\x80\xa0" => "\x86",
        "\xe2\x80\xa1" => "\x87",
        "\xcb\x86"     => "\x88",
        "\xe2\x80\xb0" => "\x89",
        "\xc5\xa0"     => "\x8a",
        "\xe2\x80\xb9" => "\x8b",
        "\xc5\x92"     => "\x8c",
        "\xc5\xbd"     => "\x8e",
        "\xe2\x80\x98" => "\x91",
        "\xe2\x80\x99" => "\x92",
        "\xe2\x80\x9c" => "\x93",
        "\xe2\x80\x9d" => "\x94",
        "\xe2\x80\xa2" => "\x95",
        "\xe2\x80\x93" => "\x96",
        "\xe2\x80\x94" => "\x97",
        "\xcb\x9c"     => "\x98",
        "\xe2\x84\xa2" => "\x99",
        "\xc5\xa1"     => "\x9a",
        "\xe2\x80\xba" => "\x9b",
        "\xc5\x93"     => "\x9c",
        "\xc5\xbe"     => "\x9e",
        "\xc5\xb8"     => "\x9f"
    ];

    /**
     * @access public
     * @var array BROKEN, Broken UTF-8 to UTF-8
     */
    public const BROKEN = [
        "\xc2\x80" => "\xe2\x82\xac",
        "\xc2\x82" => "\xe2\x80\x9a",
        "\xc2\x83" => "\xc6\x92",
        "\xc2\x84" => "\xe2\x80\x9e",
        "\xc2\x85" => "\xe2\x80\xa6",
        "\xc2\x86" => "\xe2\x80\xa0",
        "\xc2\x87" => "\xe2\x80\xa1",
        "\xc2\x88" => "\xcb\x86",
        "\xc2\x89" => "\xe2\x80\xb0",
        "\xc2\x8a" => "\xc5\xa0",
        "\xc2\x8b" => "\xe2\x80\xb9",
        "\xc2\x8c" => "\xc5\x92",
        "\xc2\x8e" => "\xc5\xbd",
        "\xc2\x91" => "\xe2\x80\x98",
        "\xc2\x92" => "\xe2\x80\x99",
        "\xc2\x93" => "\xe2\x80\x9c",
        "\xc2\x94" => "\xe2\x80\x9d",
        "\xc2\x95" => "\xe2\x80\xa2",
        "\xc2\x96" => "\xe2\x80\x93",
        "\xc2\x97" => "\xe2\x80\x94",
        "\xc2\x98" => "\xcb\x9c",
        "\xc2\x99" => "\xe2\x84\xa2",
        "\xc2\x9a" => "\xc5\xa1",
        "\xc2\x9b" => "\xe2\x80\xba",
        "\xc2\x9c" => "\xc5\x93",
        "\xc2\x9e" => "\xc5\xbe",
        "\xc2\x9f" => "\xc5\xb8"
    ];

    /**
     * @access public
     * @var array SPECIAL, Special UTF-8 chars
     */
    public const SPECIAL = [
        "\xe2\x80\x99" => "\x27",
        "\xc2\xb4"     => "\x27"
    ];

    /**
     * Init encoder.
     * 
     * @param bool $useIconv
     * @param mixed $iconvOption
     */
    public function __construct(bool $useIconv = false, $iconvOption = false)
    {
        $this->useIconv = $useIconv;
        if ( is_string($iconvOption) ) {
            $iconvOption = Stringify::remove('//', Stringify::uppercase($iconvOption));
            $this->iconvOption = Stringify::replace('|', '//', $iconvOption);
        }
    }

    /**
     * Disable special UTF-8 converting.
     * 
     * @access public
     * @param string $string
     * @param string $to
     * @param string $from
     * @return string
     */
    public function noSpecial() : self
    {
        $this->useSpecial = false;
        return $this;
    }

    /**
     * Convert string encoding.
     * 
     * @access public
     * @param string $string
     * @param string $to
     * @param string $from
     * @return string
     */
    public function convert(string $string, string $to, string $from = 'ISO-8859-1') : string
    {
        $to   = $this->formatEncoding($to);
        $from = $this->formatEncoding($from);

        // Using iconv
        if ( $this->useIconv ) {
            if ( TypeCheck::isFunction('iconv') ) {
                if ( $this->iconvOption ) {
                    $to = "{$to}//{$this->iconvOption}";
                }
                return (string)@iconv($from, $to, $string);
            }
        }

        // Using multibyte
        if ( TypeCheck::isFunction('mb_convert_encoding') ) {
            return (string)@mb_convert_encoding($string, $to, $from);
        }

        return $string;
    }

    /**
     * Sanitize UTF-8 string.
     * 
     * @access public
     * @param string $string
     * @return string
     */
    public function sanitize(string $string) : string
    {
        $last = '';
        while($last <> $string) {
            $last = $string;
            $string = $this->toUtf8($this->decodeUtf8($string));
        }
        $string = $this->toUtf8($this->decodeUtf8($string));
        return $string;
    }
    
    /**
     * Encode UTF-8 string.
     * 
     * @access public
     * @param string $string
     * @param string $from
     * @return string
     */
    public function encodeUtf8(string $string, string $from = 'ISO-8859-1') : string
    {
        $from = $this->formatEncoding($from);
        return $this->convert($string, 'UTF-8', $from);
    }

    /**
     * Decode UTF-8 string.
     * 
     * @access public
     * @param string $string
     * @param string $to
     * @return string
     */
    public function decodeUtf8(string $string, string $to = 'ISO-8859-1') : string
    {
        // Using converter
        $to = $this->formatEncoding($to);
        $decode = $this->convert($string, $to, 'UTF-8');

        // Using table
        if ( empty($decode) ) {
            $decode = $this->convertUtf8($string);
        }

        return $decode;
    }

    /**
     * Convert string to UTF-8.
     * 
     * @access public
     * @param string $string
     * @return string
     */
    public function toUtf8(string $string) : string
    {
        $max = $this->getLength($string);
        $tmp = '';

        for ($i = 0; $i < $max; $i++) {

            $c1 = $string[$i];

            // Maybe require UTF-8 converting
            if ( $this->maybeRequireConverting($c1) ) {

                $c2 = ($i + 1 >= $max) ? "\x00" : $string[$i+1];
                $c3 = ($i + 2 >= $max) ? "\x00" : $string[$i+2];
                $c4 = ($i + 3 >= $max) ? "\x00" : $string[$i+3];

                // Maybe 2 bytes UTF-8
                if ( $this->maybe2Bytes($c1) ) {

                    // Valid UTF-8
                    if ( $this->isValidBytes($c2) ) {
                        $tmp .= "{$c1}{$c2}";
                        $i++;
                    
                    // Convert char to UTF-8
                    } else {
                        $tmp .= $this->convertChar($c1);
                    }

                // Maybe 3 bytes UTF8
                } elseif ( $this->maybe3Bytes($c1) ) {

                    // Valid UTF-8
                    if ( $this->isValidBytes($c2) && $this->isValidBytes($c3) ) {
                        $tmp .= "{$c1}{$c2}{$c3}";
                        $i += 2;
                    
                    // Convert char to UTF-8
                    } else {
                        $tmp .= $this->convertChar($c1);
                    }

                // Maybe 4 bytes UTF8
                } elseif ( $this->maybe4Bytes($c1) ) {

                    // Valid UTF-8
                    if ( $this->isValidBytes($c2) && $this->isValidBytes($c3) && $this->isValidBytes($c4) ) {
                        $tmp .= "{$c1}{$c2}{$c3}{$c4}";
                        $i += 3;
                    
                    // Convert char to UTF-8
                    } else {
                        $tmp .= $this->convertChar($c1);
                    }

                // Force convert char to UTF-8
                } else {
                    $tmp .= $this->convertChar($c1);
                }

            // Require UTF-8 converting
            } elseif ( $this->requireConverting($c1) ) {

                // Convert Windows-1252 to UTF-8
                if ( $this->isWindows1252($c1) ) {
                    $tmp .= $this->convertWindows1252($c1);

                // Force convert char to UTF-8
                } else {
                    $tmp .= $this->convertChar($c1);
                }

            // Valid UTF-8
            } else {
                $tmp .= $c1;
            }
        }

        // Convert special UTF-8
        if ( $this->useSpecial ) {
            $tmp = $this->convertSpecial($tmp);
        }

        return $tmp;
    }

    /**
     * Convert string to Windows-1252,
     * [Alias].
     * 
     * @access public
     * @param string $string
     * @return string
     */
    public function toWindows1252(string $string) : string
    {
        return $this->decodeUtf8($string);
    }

    /**
     * Convert string to Latin-1,
     * [Alias].
     * 
     * @access public
     * @param string $string
     * @return string
     */
    public function toLatin1(string $string) : string
    {
        return $this->decodeUtf8($string);
    }

    /**
     * Remove BOM from UTF-8.
     * 
     * @access public
     * @param string $string
     * @return string
     */
    public static function unBom(string $string) : string
    {
        if ( substr($string, 0, 3) == pack('CCC', 0xef, 0xbb, 0xbf) ) {
            $string = substr($string, 3);
        }
        return $string;
    }

    /**
     * Fix broken UTF-8 string.
     * 
     * @access public
     * @param string $string
     * @return string
     */
    public static function unbreak(string $string) : string
    {
        $search  = Arrayify::keys(static::BROKEN);
        $replace = Arrayify::values(static::BROKEN);
        return Stringify::replace($search, $replace, $string);
    }

    /**
     * Detect string encoding.
     *
     * @access public
     * @param string $string
     * @param mixed $encodings
     * @return mixed
     */
    public static function detect(string $string, $encodings = null)
    {
        // Using multibyte
        if ( TypeCheck::isFunction('mb_detect_encoding') ) {
            return mb_detect_encoding($string, $encodings, true);
        }
        return false;
    }

    /**
     * Check Windows-1252 chars table.
     * 
     * @access protected
     * @param string $string
     * @return bool
     */
    protected function isWindows1252(string $string) : bool
    {
        $n = $this->toInt($string);
        return isset(static::WINDOWS1252[$n]);
    }

    /**
     * Convert Windows-1252 chars to UTF-8 using table.
     *
     * @access protected
     * @param string $string
     * @return string
     */
    protected function convertWindows1252(string $string) : string
    {
        $n = $this->toInt($string);
        return static::WINDOWS1252[$n] ?? '';
    }

    /**
     * Convert UTF-8 chars to Windows-1252 using table.
     *
     * @access protected
     * @param string $string
     * @return string
     */
    protected function convertUtf8(string $string) : string
    {
        $search  = Arrayify::keys(static::UTF8);
        $replace = Arrayify::values(static::UTF8);
        return Stringify::replace($search, $replace, $this->toUtf8($string));
    }

    /**
     * Convert special UTF-8 string using table.
     * 
     * @access protected
     * @param string $string
     * @return string
     */
    protected function convertSpecial(string $string) : string
    {
        $search  = Arrayify::keys(static::SPECIAL);
        $replace = Arrayify::values(static::SPECIAL);
        return Stringify::replace($search, $replace, $string);
    }

    /**
     * Convert char to UTF-8.
     *
     * @access protected
     * @param string $string
     * @return string
     */
    protected function convertChar(string $string) : string
    {
        $char1 = (chr(ord($string) / 64) | "\xc0");
        $char2 = (($string & "\x3f") | "\x80");
        return "{$char1}{$char2}";
    }

    /**
     * Convert the first byte of a string to a value between 0 and 255.
     *
     * @access protected
     * @param string $string
     * @return int
     */
    protected function toInt(string $string) : int
    {
        return ord($string);
    }

    /**
     * Get string length.
     *
     * @access protected
     * @param string $string
     * @return int
     */
    protected function getLength(string $string) : int
    {
        // Using multibyte
        if ( TypeCheck::isFunction('mb_strlen')
        && ( (int)System::getIni('mbstring.func_overload')) == 2 ) {
            return (int)mb_strlen($string, '8bit');
        }
        return strlen($string);
    }

    /**
     * Maybe require UTF-8 converting.
     *
     * @access protected
     * @param string $char
     * @return bool
     */
    protected function maybeRequireConverting(string $char) : bool
    {
        return ($char >= "\xc0");
    }

    /**
     * Require UTF-8 converting.
     *
     * @access protected
     * @param string $char
     * @return bool
     */
    protected function requireConverting(string $char) : bool
    {
        return (($char & "\xc0") == "\x80");
    }

    /**
     * Check valid UTF-8 bytes.
     *
     * @access protected
     * @param string $char
     * @return bool
     */
    protected function isValidBytes(string $char) : bool
    {
        return ($char >= "\x80" && $char <= "\xbf");
    }

    /**
     * Maybe 2 bytes UTF-8.
     *
     * @access protected
     * @param string $char
     * @return bool
     */
    protected function maybe2Bytes(string $char) : bool
    {
        return ($char >= "\xc0" && $char <= "\xdf");
    }

    /**
     * Maybe 3 bytes UTF-8.
     *
     * @access protected
     * @param string $char
     * @return bool
     */
    protected function maybe3Bytes(string $char) : bool
    {
        return ($char >= "\xe0" && $char <= "\xef");
    }

    /**
     * Maybe 4 bytes UTF-8.
     *
     * @access protected
     * @param string $char
     * @return bool
     */
    protected function maybe4Bytes(string $char) : bool
    {
        return ($char >= "\xf0" && $char <= "\xf7");
    }

    /**
     * Format encoding.
     *
     * @access protected
     * @param string $encoding
     * @return string
     */
    protected function formatEncoding(string $encoding) : string
    {
        $encoding = Stringify::uppercase($encoding);
        $encoding = Stringify::replaceRegex('/[^a-zA-Z0-9\s]/', '', $encoding);
        $format = [
          'ISO88591'    => 'ISO-8859-1',
          'ISO8859'     => 'ISO-8859-1',
          'ISO'         => 'ISO-8859-1',
          'LATIN1'      => 'ISO-8859-1',
          'LATIN'       => 'ISO-8859-1',
          'WIN1252'     => 'ISO-8859-1',
          'WINDOWS1252' => 'ISO-8859-1'
        ];
        return $format[$encoding] ?? 'UTF-8';
    }
}
