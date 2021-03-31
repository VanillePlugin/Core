<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.5.5
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\inc\File;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\Server;
use VanillePlugin\int\PluginNameSpaceInterface;

final class Rewrite extends PluginOptions
{
    /**
     * @access private
     * @var string $rules
     * @var array $vars
     */
    private $rules = '';
    private $vars = [];

    /**
     * @param PluginNameSpaceInterface $plugin
     */
    public function __construct(PluginNameSpaceInterface $plugin)
    {
        // Init plugin config
        $this->initConfig($plugin);

        // Load default rewrite
        if ( ($rewrite = File::r("{$this->getRoot()}/core/storage/config/rewrite")) ) {
            $this->rules = $rewrite;
        }
    }

    /**
     * Set rules
     *
     * @access public
     * @param string $rules
     * @return void
     */
    public function setRules($rules = '')
    {
        $this->rules = $rules;
    }

    /**
     * Add rules
     *
     * @access public
     * @param string $regex
     * @param string $query
     * @param string $after
     * @return void
     *
     * Action : init
     */
    public function addRules($regex, $query, $after = 'bottom')
    {
        add_rewrite_rule($regex,$query,$after);
    }

    /**
     * Add endpoint
     *
     * @access public
     * @param string $name
     * @param int $places
     * @param mixed $query true
     * @return void
     *
     * Action : init
     * EP_ALL : 8191
     */
    public function addEndpoint($name, $places = 8191, $query = true)
    {
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
     * Apply rules
     *
     * @access public
     * @param int $priority
     * @return void
     *
     * Action : admin_init
     */
    public function applyRules($priority = 10)
    {
        $this->addFilter('mod_rewrite_rules',[$this,'getRules'],$priority);
    }

    /**
     * Remove rules
     *
     * @access public
     * @param int $priority
     * @return void
     *
     * Action : admin_init
     */
    public function removeRules($priority = 10)
    {
        $this->removeFilter('mod_rewrite_rules',[$this,'getRules'],$priority);
    }

    /**
     * @access public
     * @param string $rules
     * @return string
     *
     * Filter : mod_rewrite_rules
     */
    public function getRules($rules)
    {
        if ( Server::isApache() ) {
            $this->rules = Stringify::replaceArray($this->vars,$this->rules);
            $this->rules = Stringify::replace('{root}',site_url('','relative'),$this->rules);
            $rules = "{$rules}{$this->rules}";
        }
        return $rules;
    }

    /**
     * @access public
     * @param string $rules
     * @return bool
     */
    public static function hasRules($rules)
    {
        if ( File::exists( $htaccess = ABSPATH . '/.htaccess') ) {
            return Stringify::contains(File::r($htaccess), $rules);
        }
        return false;
    }

    /**
     * @access public
     * @param bool $force
     * @return void
     */
    public static function flush($force = true)
    {
        flush_rewrite_rules($force);
    }

    /**
     * @access public
     * @param void
     * @return bool
     */
    public static function backup()
    {
        if ( File::exists( $htaccess = ABSPATH . '/.htaccess') ) {
            if ( !File::exists( $backup = ABSPATH . '.htaccess.backup') ){
                return File::w($backup, File::r($htaccess));
            }
        }
        return false;
    }
}
