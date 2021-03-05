<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.4.0
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\inc\File;
use VanillePlugin\inc\Stringify;

final class Rewrite extends WordPress
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
     * Action : init
     * 
     * @access public
     * @param string $regex
     * @param string $query
     * @param string $after
     * @return void
     */
    public function addRules($regex, $query, $after = 'bottom')
    {
        // Add rules
        add_rewrite_rule($regex,$query,$after);
    }

    /**
     * Action : init
     *
     * @access public
     * @param string $name
     * @param int $places 8191
     * @param mixed $query true
     * @return void
     *
     * EP_ALL : 8191
     */
    public function addEndpoint($name, $places = 8191, $query = true)
    {
        // Add endpoint
        add_rewrite_endpoint($name,$places,$query);
    }

    /**
     * @access public
     * @param array $vars
     * @return void
     */
    public function addVars($vars = [])
    {
        $this->vars = $vars;
    }

    /**
     * @access public
     * @param int $priority 90
     * @return void
     */
    public function applyRules($priority = 90)
    {
        // Apply rules
        $this->addFilter('mod_rewrite_rules',[$this,'getRules'],$priority);
        $this->flush();
    }

    /**
     * @access public
     * @param int $priority 90
     * @return void
     */
    public function removeRules($priority = 90)
    {
        // Remove rules
        $this->removeFilter('mod_rewrite_rules',[$this,'getRules'],$priority);
        $this->flush();
    }

    /**
     * Filter : mod_rewrite_rules
     *
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
     * @param bool $force true
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
    public function backup()
    {
        if ( File::exists( $htaccess = ABSPATH . '/.htaccess') ) {
            if ( !File::exists( $backup = ABSPATH . '.htaccess.backup') ){
                File::w($backup, File::r($htaccess));
            }
        }
    }
}
