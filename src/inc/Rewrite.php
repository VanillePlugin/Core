<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.2.6
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
     * @return array $vars
     */
    private $rules;
    private $vars = [];

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
     * @param string $name
     * @param int $places
     * @return void
     */
    public function addEndpoint($name, $places)
    {
        // Add endpoint
        add_rewrite_endpoint($name, $places);
    }

    /**
     * @access public
     * @param string $vars
     * @return void
     */
    public function addVars($vars = [])
    {
        $this->vars = $vars;
    }

    /**
     * @access public
     * @param void
     * @return void
     */
    public function applyRules()
    {
        // Apply rules
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
        if ($this->vars) {
            $this->rules = Stringify::replaceArray($this->vars, $this->rules);
        }
        return $this->rules;
    }

    /**
     * @access public
     * @param boolean $force
     * @return void
     */
    public function flush($force = true)
    {
       flush_rewrite_rules($force);
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
            if ( !File::exists( $backup = ABSPATH . '.htaccess.backup') ){
                File::w($backup, File::r($htaccess));
            }
        }
    }
}
