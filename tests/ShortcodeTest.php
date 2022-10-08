<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.9
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 */

use VanillePlugin\inc\Shortcode;
use PHPUnit\Framework\TestCase;

final class ShortcodeTest extends TestCase
{
    public function testAttributes()
    {
        $default = ['att1' => '','att2' => '2','att3' => '','att4' => 4];
        $atts    = ['att1' => '1','att2' => 2,'att3' => '3'];
        $atts = Shortcode::attributes($default,$atts,'');
        $this->assertEquals($atts, ['att1' => '1','att2' => 2,'att3' => '3','att4' => 4]);
    }

    public function testFormatAttributes()
    {
        $atts = ['att-1' => '','att-2' => 1,'ATT-3' => '3'];
        $atts = Shortcode::formatAttributes($atts);
        $this->assertEquals($atts, ['att_1' => '','att_2' => 1,'att_3' => '3']);
    }

    public function testFormatAttributeName()
    {
        $this->assertSame(Shortcode::formatAttributeName('att-1'),'att_1');
        $this->assertSame(Shortcode::formatAttributeName('att--1'),'att__1');
        $this->assertSame(Shortcode::formatAttributeName('att---1'),'att___1');
    }

    public function testFormatSeparator()
    {
        $this->assertSame(Shortcode::formatSeparator('1|2|3'),'1,2,3');
        $this->assertSame(Shortcode::formatSeparator('1;2;3'),'1,2,3');
        $this->assertSame(Shortcode::formatSeparator('1; 2; 3',true),'1,2,3');
    }

    // public function testSetAttsValues()
    // {
    //     $atts = Shortcode::setAttsValues(['att-1','att-2','ATT-3']);
    //     $this->assertEquals($atts, ['att_1' => '','att_2' => '','att_3' => '']);
    // }

    // public function testHasAttribute()
    // {
    //     $this->assertTrue(Shortcode::hasAttribute(['att_1' => '','att-2' => '','ATT-3' => ''],'att-3'));
    //     $this->assertTrue(Shortcode::hasAttribute(['att_1' => '','att-2' => '','att-3' => ''],'ATT-3'));
    //     $this->assertTrue(Shortcode::hasAttribute(['att_1' => '','att-2' => '','att-3' => ''],'att_1'));
    //     $this->assertTrue(Shortcode::hasAttribute(['att_1' => '','att-2' => '','att_3' => ''],'att-1'));
    //     $this->assertFalse(Shortcode::hasAttribute(['att_1' => '','att-2' => '','ATT-3' => ''],'att-4'));
    //     $this->assertFalse(Shortcode::hasAttribute(['att_1','att-2' => '','ATT-3' => ''],'att-1')); // Flag
    // }

    // public function testHasFlag()
    // {
    //     $this->assertTrue(Shortcode::hasFlag(['att-1','att-2' => '2','att-3',4],'att-1'));
    //     $this->assertTrue(Shortcode::hasFlag(['att-1','att-2' => '2','att-3',4],'att_1'));
    //     $this->assertTrue(Shortcode::hasFlag(['ATT-1','att-2' => '2','att-3',4],'att-1'));
    //     $this->assertTrue(Shortcode::hasFlag(['att-1','att-2' => '2','att-3',4],'ATT-1'));
    //     $this->assertTrue(Shortcode::hasFlag(['att-1','att-2' => '2','att-3',4],'ATT-3'));
    //     $this->assertFalse(Shortcode::hasFlag(['att-1','att-2' => '2','att-3',4],'att-2')); // Non Flag
    //     $this->assertFalse(Shortcode::hasFlag(['att-1','att-2' => '2','att-3',4],4)); // Non Flag
    // }

    // public function testGetValue()
    // {
    //     $atts = ['att-1','att-2' => '2','att-3' => 3,'att-4' => false,'att-5' => []];
    //     $this->assertSame(Shortcode::getValue($atts,'att-1'), null);
    //     $this->assertSame(Shortcode::getValue($atts,'att-2'), '2');
    //     $this->assertSame(Shortcode::getValue($atts,'att-3'), 3);
    //     $this->assertSame(Shortcode::getValue($atts,'att-2','int'), 2);
    //     $this->assertSame(Shortcode::getValue($atts,'att-2','bool'), true);
    //     $this->assertSame(Shortcode::getValue($atts,'att-4'), false);
    //     $this->assertSame(Shortcode::getValue($atts,'att-5'), []);
    // }

    // public function testHasValue()
    // {
    //     $atts = ['att-1','att-2' => '2','att-3' => 3,'att-4' => false,'att-5' => []];
    //     $this->assertTrue(Shortcode::hasValue($atts,'att-2','2'));
    //     $this->assertTrue(Shortcode::hasValue($atts,'att-3',3));
    //     $this->assertTrue(Shortcode::hasValue($atts,'att-4',false));
    //     $this->assertTrue(Shortcode::hasValue($atts,'att-5',[]));
    //     $this->assertFalse(Shortcode::hasValue($atts,'att-2',2));
    // }

    // public function testIsEmpty()
    // {
    //     $atts = ['att-1','att-2' => '2','att-3' => 3,'att-4' => false,'att-5' => []];
    //     $this->assertTrue(Shortcode::isEmpty($atts,'att-2','2'));
    //     $this->assertTrue(Shortcode::hasValue($atts,'att-3',3));
    //     $this->assertTrue(Shortcode::hasValue($atts,'att-4',false));
    //     $this->assertTrue(Shortcode::hasValue($atts,'att-5',[]));
    //     $this->assertFalse(Shortcode::hasValue($atts,'att-2',2));
    // }
}
