<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.1
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

/**
 * Plugin rewrite.
 */
final class Rewrite
{
    use \VanillePlugin\VanillePluginConfig,
        \VanillePlugin\tr\TraitHookable;
    
    /**
     * @access private
     * @var string $rules
     * @var array $vars
     */
    private $rules = '';
    private $vars = [];

    /**
     * Init rewrite.
     */
    public function __construct()
    {
		// Init config
		$this->initConfig();

        // Load default rewrite
        $dir = "{$this->getRoot()}/core/storage/config/";
        if ( $this->isFile( $rewrite = "{$dir}/.rewrite" ) ) {
            $this->rules = $this->writeFile($rewrite);
        }

		// Reset config
		$this->resetConfig();
    }

    /**
     * Set rules.
     *
     * @access public
     * @param string $rules
     * @return void
     */
    public function setRules(string $rules)
    {
        $this->rules = $rules;
    }

    /**
     * Add rules,
     * [Action: init].
     *
     * @access public
     * @param string $regex
     * @param mixed $query
     * @param string $after
     * @return void
     */
    public function addRules(string $regex, $query, string $after = 'bottom')
    {
        add_rewrite_rule($regex, $query, $after);
    }

    /**
     * Add endpoint,
     * [Action: init],
     * [EP_ALL: 8191].
     *
     * @access public
     * @param string $name
     * @param int $places
     * @param mixed $query
     * @return void
     */
    public function addEndpoint(string $name, int $places = 8191, $query = true)
    {
        add_rewrite_endpoint($name, $places, $query);
    }

    /**
     * Add vars.
     * 
     * @access public
     * @param array $vars
     * @return void
     */
    public function addVars($vars = [])
    {
        $this->vars = $vars;
    }

    /**
     * Apply rules,
     * [Action: admin_init].
     *
     * @access public
     * @param int $priority
     * @return void
     */
    public function applyRules(int $priority = 10)
    {
        $this->addFilter('mod_rewrite_rules', [$this, 'getRules'], $priority);
    }

    /**
     * Remove rules,
     * [Action: admin_init].
     *
     * @access public
     * @param int $priority
     * @return bool
     */
    public function removeRules(int $priority = 10) : bool
    {
        return $this->removeFilter('mod_rewrite_rules', [$this, 'getRules'], $priority);
    }

    /**
     * Get rules,
     * [Filter: mod_rewrite_rules].
     * 
     * @access public
     * @param string $rules
     * @return string
     */
    public function getRules(string $rules) : string
    {
        $this->rules = $this->replaceStringArray($this->vars, $this->rules);
        $this->rules = $this->replaceString('{root}', $this->geSiteUrl(), $this->rules);
        $rules = "{$rules}{$this->rules}";
        return $rules;
    }

    /**
     * Check rules.
     * 
     * @access public
     * @param string $rules
     * @return bool
     */
    public function hasRules(string $rules) : bool
    {
        if ( $this->isFile( $htaccess = ABSPATH . '/.htaccess' ) ) {
            return $this->hasString($this->writeFile($htaccess), $rules);
        }
        return false;
    }

    /**
     * Flush rules.
     * 
     * @access public
     * @param bool $force
     * @return void
     */
    public static function flush(bool $force = true)
    {
        flush_rewrite_rules($force);
    }

    /**
     * Backup rules.
     * 
     * @access public
     * @return bool
     */
    public function backup() : bool
    {
        if ( $this->isFile( $htaccess = ABSPATH . '/.htaccess' ) ) {
            if ( !$this->isFile( $backup = ABSPATH . '.htaccess.backup' ) ){
                return $this->writeFile($backup, $this->writeFile($htaccess));
            }
        }
        return false;
    }
}
