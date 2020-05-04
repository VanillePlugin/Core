<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.0.1
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

use VanillePlugin\lib\WordPress;

class Rewrite extends WordPress
{
    /**
     * @access private
     * @return string $rules
     */
    private $rules;

    /**
     * @param string $rules
     * @return void
     */
    public function __construct($rules = '')
    {
        // Init rules
        $this->rules = $rules;
    }

    /**
     * @access public
     * @param string $regex
     * @param string $redirect
     * @param string $after
     * @return void
     */
    public function addRules($regex, $redirect, $after = 'bottom')
    {
        // Add rules
        add_rewrite_rule($regex, $redirect, $after);
    }

    /**
     * @access public
     * @param void
     * @return void
     */
    public function applyRules()
    {
        // Apply rules
       $this->backupHtaccess();
       $this->addFilter('mod_rewrite_rules', [$this, 'getRules'], 90);
       $this->flushRewrite();
    }

    /**
     * @access public
     * @param void
     * @return void
     */
    public function removeRules()
    {
        // Remove rules
       $this->removeFilter('mod_rewrite_rules', [$this, 'getRules'], 90);
       $this->flushRewrite();
    }

    /**
     * @access public
     * @param void
     * @return string
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @access public
     * @param void
     * @return void
     */
    public function flushRewrite()
    {
       flush_rewrite_rules();
    }

    /**
     * @access public
     * @param void
     * @return void
     */
    public function backupHtaccess()
    {
        if ( File::exists( $htaccess = ABSPATH . '/.htaccess') ) {
            $date = date('dmY');
            if ( !File::exists( $backup = ABSPATH . ".htaccess-{$date}.backup") ){
                File::write($backup, File::read($htaccess));
            }
        }
    }
}
