<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin;

/**
 * Define tweaked base functions used by plugin.
 *
 * - Hooking
 * - Rendering
 * - Authentication
 * - Configuration
 * - Translation
 * - Formatting
 * - IO
 * - Caching
 * - Requesting
 *
 * @see https://developer.wordpress.org/plugins/
 */
trait VanillePluginOption
{
	use VanillePluginBase,
		VanillePluginConfig,
		\VanillePlugin\tr\TraitCacheable,
		\VanillePlugin\tr\TraitRequestable;

	/**
	 * Add plugin action.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addPluginAction(string $hook, $callback, int $priority = 10, int $args = 1)
	{
		$hook = $this->applyNamespace($hook);
		$this->addAction($hook, $callback, $priority, $args);
	}

	/**
	 * Remove plugin action.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function removePluginAction(string $hook, $callback, int $priority = 10) : bool
	{
		$hook = $this->applyNamespace($hook);
		return $this->removeAction($hook, $callback, $priority);
	}

	/**
	 * Call plugin action.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function doPluginAction(string $hook, ...$args)
	{
		$hook = $this->applyNamespace($hook);
		$this->doAction($hook, ...$args);
	}

	/**
	 * Check whether plugin has action.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function hasPluginAction(string $hook, $callback = false)
	{
		$hook = $this->applyNamespace($hook);
		return $this->hasAction($hook, $callback);
	}

	/**
	 * Add plugin filter.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addPluginFilter(string $hook, $callback, $priority = 10, $args = 1)
	{
		$hook = $this->applyNamespace($hook);
		$this->addFilter($hook, $callback, $priority, $args);
	}

	/**
	 * Remove plugin filter.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function removePluginFilter(string $hook, $callback, int $priority = 10) : bool
	{
		$hook = $this->applyNamespace($hook);
		return $this->removeFilter($hook, $callback, $priority);
	}

	/**
	 * Apply plugin filter.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function applyPluginFilter(string $hook, $value, ...$args)
	{
		$hook = $this->applyNamespace($hook);
		return $this->applyFilter($hook, $value, ...$args);
	}

	/**
	 * Check whether plugin has filter.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function hasPluginFilter(string $hook, $callback = false)
	{
		$hook = $this->applyNamespace($hook);
		return $this->hasFilter($hook, $callback);
	}
	
	/**
	 * Add plugin shortcode.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addPluginShortcode($callback)
	{
		$tag = $this->getNameSpace();
		$this->addShortcode($tag, $callback);
	}

	/**
	 * Remove plugin shortcode.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function removePluginShortcode()
	{
		$tag = $this->getNameSpace();
		$this->removeShortcode($tag);
	}

	/**
	 * Check whether shortcode registered.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function hasPluginShortcode() : bool
	{
		$tag = $this->getNameSpace();
		return $this->hasShortcode($tag);
	}

	/**
	 * Register plugin settings.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function registerPluginOption(string $group, string $key, array $args = [], bool $multi = true)
	{
		$key = $this->getOptionLang($key, $multi);
		$group = $this->applyPrefix($group);
		$this->registerOption($group, $key, $args);
	}

	/**
	 * Add plugin option.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addPluginOption(string $key, $value, bool $multi = true) : bool
	{
		$key = $this->getOptionLang($key, $multi);
		return $this->addOption($key, $value);
	}

	/**
	 * Get plugin option.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getPluginOption(string $key, $default = [], bool $multi = true)
	{
		$key = $this->getOptionLang($key, $multi);
		$value = $this->stripSlash(
			$this->getOption($key, $default)
		);
		return $value;
	}

	/**
	 * Update plugin option.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function updatePluginOption(string $key, $value, bool $multi = true) : bool
	{
		$key = $this->getOptionLang($key, $multi);
		return $this->updateOption($key, $value);
	}

	/**
	 * Remove plugin option.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function removePluginOption(string $key, bool $multi = true) : bool
	{
		$key = $this->getOptionLang($key, $multi);
		return $this->removeOption($key);
	}

	/**
	 * Get plugin option language.
	 *
	 * @access protected
	 * @param string $key
	 * @param bool $multi
	 * @return string
	 */
	protected function getOptionLang(string $key, bool $multi) : string
	{
		if ( $this->hasMultilingual() && $multi ) {
			$key = "{$key}-{$this->getLang()}";
		}
		return $this->applyPrefix($key);
	}

	/**
	 * Set plugin transient lang.
	 * [Action: head].
	 * [Action: admin-init].
	 *
	 * @access protected
	 * @return bool
	 */
	protected function setLang() : bool
	{
		if ( $this->hasMultilingual() ) {
			$lang = $this->getLang();
			return $this->setPluginTransient('lang', $lang, 0);
		}
		return false;
	}

	/**
	 * Check settings save action (No JS).
	 *
	 * @access protected
	 * @return bool
	 */
	protected function saved() : bool
	{
		$action = $this->applyNamespace('settings-updated');
		if ( $this->hasRequest($action) && $this->getRequest($action) == 'true' ) {
			return true;
		}
		return false;
	}

	/**
	 * Remove all namespace options.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function removePluginOptions() : bool
	{
		return $this->removeOptions($this->getNameSpace());
	}

	/**
	 * Add plugin menu page.
	 * [Filter: {plugin}-menu-name].
	 * [Filter: {plugin}-menu-icon].
	 * [Filter: {plugin}-menu-cap].
	 * [Filter: {plugin}-menu-pos].
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addPluginMenuPage(array $settings = []) : string
	{
		$name = $this->getPluginName();
		$name = $this->applyPluginFilter('menu-name', $name);
		$icon = $this->applyPluginFilter('menu-icon', 'admin-plugins');
		$cap  = $this->applyPluginFilter('menu-cap', $this->applySufix('manage'));
		$pos  = $this->applyPluginFilter('menu-pos', 20);

		$settings = $this->mergeArray([
			'title'    => "{$name} Dashboard",
			'menu'     => $name,
			'cap'      => $cap,
			'slug'     => $this->getNameSpace(),
			'callback' => [$this, 'index'],
			'icon'     => $icon,
			'position' => $pos
		], $settings);

		$settings['title'] = $this->translate($settings['title']);
		$settings['menu']  = $this->translate($settings['menu']);

		return $this->addMenuPage($settings);
	}

	/**
	 * Add plugin submenu page.
	 * [Filter: {plugin}-menu-name].
	 * [Filter: {plugin}-menu-cap].
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addPluginSubMenuPage(array $settings = [])
	{
		$name = $this->getPluginName();
		$name = $this->applyPluginFilter('menu-name', $name);

		$cap = $this->applySufix('manage');
		$cap = $this->applyPluginFilter('menu-cap', $cap);

		$settings = $this->mergeArray([
			'parent'   => $this->getNameSpace(),
			'title'    => "{$name} {menu}",
			'menu'     => $name,
			'cap'      => $cap,
			'slug'     => $this->getNameSpace(),
			'callback' => [$this, 'index'],
			'icon'     => false
		], $settings);
		
		$settings['menu']  = $this->translate($settings['menu']);
		$settings['title'] = $this->translate(
			$this->replaceString('{menu}', $settings['menu'], $settings['title'])
		);
		
		if ( $settings['slug'] ) {
			$settings['slug'] = $this->applyNameSpace($settings['slug']);
		}

		return $this->addSubMenuPage($settings);
	}

	/**
	 * Reset plugin submenu.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function resetPluginSubMenu(?string $title = null, ?string $icon = null)
	{
		$parent = $this->getNameSpace();
		$title  = $this->translate($title);
		$this->resetSubMenuPage($parent, $title, $icon);
	}

	/**
	 * Check plugin screen.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isPluginScreen() : bool
	{
		return $this->isScreen($this->getNameSpace());
	}

	/**
	 * Add plugin JS.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addPluginJS(string $path, array $deps = [], $version = false, bool $footer = true)
	{
		$id = $this->removeString('.js', $this->basename($path));
		$id = $this->removeString('.min', $id);
		$id = $this->applyNamespace($id);
		$path = $this->applyAsset($path);
		$this->addJS($id, $path, $deps, $version, $footer);
	}

	/**
	 * Add plugin main JS.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addPluginMainJS(string $path, array $deps = [], $version = false, bool $footer = true)
	{
		$id = $this->applyNamespace('main');
		$path = $this->applyAsset($path);
		$this->addJS($id, $path, $deps, $version, $footer);
	}

	/**
	 * Add plugin global JS.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addPluginGlobalJS(string $path, array $deps = [], $version = false, bool $footer = true)
	{
		$id = $this->applyNamespace('global');
		$path = $this->applyAsset($path);
		$this->addJS($id, $path, $deps, $version, $footer);
	}

	/**
	 * Assign plugin JS data.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function assignPluginJS(array $data = [], string $id = 'main')
	{
		$id = $this->applyNamespace($id);
		$object = $this->applyPrefix('Plugin', false);
		$this->assignJS($id, $object, $data);
	}

	/**
	 * Assign plugin global JS data.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function assignGlobalJS(array $data = [], string $id = 'global')
	{
		$id = $this->applyNamespace($id);
		$object = $this->applyPrefix('Global', false);
		$this->assignJS($id, $object, $data);
	}

	/**
	 * Add plugin style.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addPluginCSS(string $path, array $deps = [], $version = false, string $media = 'all')
	{
		$id = $this->removeString('.css', $this->basename($path));
		$id = $this->removeString('.min', $id);
		$id = $this->applyNamespace($id);
		$path = $this->applyAsset($path);
		$this->addCSS($id, $path, $deps, $version, $media);
	}

	/**
	 * Add plugin main style.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addPluginMainCSS(string $path, array $deps = [], $version = false, string $media = 'all')
	{
		$id = $this->applyNamespace('main');
		$path = $this->applyAsset($path);
		$this->addCSS($id, $path, $deps, $version, $media);
	}

	/**
	 * Add plugin global style.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addPluginGlobalCSS(string $path, array $deps = [], $version = false, string $media = 'all')
	{
		$id = $this->applyNamespace('global');
		$path = $this->applyAsset($path);
		$this->addCSS($id, $path, $deps, $version, $media);
	}

	/**
	 * Check whether page is plugin admin.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isPluginAdmin(?string $slug = null) : bool
	{
		$protocol = $this->getServerProtocol();
		$host = $this->getServer('http-host');
		$request = $this->getServer('request-uri');
		$url = "{$protocol}{$host}{$request}";
		$current = ($slug) ? $slug : $this->applySufix('?page=', false);
		return $this->hasString($url, $current);
	}
	
	/**
	 * Add plugin capability.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addPluginCapability(string $role, string $cap = 'manage')
	{
		$cap = $this->applySufix($cap);
		$this->addCapability($role, $cap);
	}

	/**
	 * Add plugin capabilities.
	 * [Filter: {plugin}-roles].
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addPluginCaps($roles = [], string $cap = 'manage')
	{
		if ( $this->isType('string', $roles) ) {
			$roles = [$roles];
		}
		$roles = $this->getPluginRoles();
		$roles = $this->applyPluginFilter('roles', $roles);
		foreach ($roles as $role) {
			$this->addPluginCapability($role, $cap);
		}
	}

	/**
	 * Check plugin capability.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function hasPluginCap(string $cap = 'manage') : bool
	{
		$cap = $this->applySufix($cap);
		return $this->hasCap($cap);
	}

	/**
	 * Remove plugin capability.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function removePluginCap(string $role, string $cap = 'manage')
	{
		$cap = $this->applySufix($cap);
		$this->removeCap($role, $cap);
	}

	/**
	 * Remove plugin capabilities.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function removePluginCaps($roles = [], string $cap = 'manage')
	{
		$this->purgePluginTransients();
		if ( $this->isType('string', $roles) ) {
			$roles = [$roles];
		}
		if ( empty($roles) ) {
			$roles = $this->getSiteRoles();
		}
		foreach ($roles as $role) {
			$this->removePluginCap($role, $cap);
		}
	}

	/**
	 * Create token.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function createToken($action = null) : string
	{
		if ( $this->isType('string', $action) ) {
			if ( empty($action) ) {
				$action = 'verify';
			}
			$action = $this->applyNamespace($action);
		}
	  	return $this->createNonce($action);
	}

	/**
	 * Verify token.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function verifyToken($action = null, bool $strict = false)
	{
		if ( $this->isType('string', $action) ) {
			if ( empty($action) ) {
				$action = 'verify';
			}
			$action = $this->applyNamespace($action);
		}

		if ( $this->hasRequest('nonce') ) {
			$token = $this->getRequest('nonce');

		} else {
			$token = $this->applyNamespace('token');
		}

	    if ( !$this->checkNonce($token, $action) ) {
	    	if ( $strict ) {
	    		die($this->translate('Invalid token'));
	    	}
	    	$code = ($this->hasDebug()) ? 400 : 200;
	    	$this->setResponse('Invalid token', [], 'error', $code);
	    }
	}

	/**
	 * Verify AJAX token.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function verifyAjaxToken($action = null, bool $strict = false)
	{
		if ( $this->isType('string', $action) ) {
			if ( empty($action) ) {
				$action = 'verify';
			}
			$action = $this->applyNamespace($action);
		}

		if ( $this->hasRequest('nonce') ) {
			$token = $this->getRequest('nonce');
			
		} else {
			$token = $this->applyNamespace('token');
		}

		if ( !$this->checkAjaxNonce($token, $action) ) {
	    	if ( $strict ) {
	    		die($this->translate('Invalid token'));
	    	}
	    	$code = ($this->hasDebug()) ? 400 : 200;
	    	$this->setResponse('Invalid token', [], 'error', $code);
	  	}
	}

	/**
	 * Verify role permission.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function verifyPermission($id = null)
	{
		if ( !$this->isAdministrator($id) ) {
			$code = ($this->hasDebug()) ? 401 : 200;
			$this->setResponse("You don't have permissions", [], 'error', $code);
			return false;
		}
	}

	/**
	 * Get plugin transient.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getPluginTransient(string $key)
	{
		$key = $this->applyPrefix($key);
		if ( $this->isMultisite() && $this->allowedMultisite() ) {
			return $this->getSiteTransient($key);
		}
		return $this->getTransient($key);
	}

	/**
	 * Set plugin transient.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function setPluginTransient(string $key, $value, ?int $ttl = null) : bool
	{
		$key = $this->applyPrefix($key);
		if ( $this->isType('null', $ttl) ) {
			$ttl = $this->getExpireIn();
		}
		if ( $this->isMultisite() && $this->allowedMultisite() ) {
			return $this->setSiteTransient($key, $value, $ttl);
		}
		return $this->setTransient($key, $value, $ttl);
	}

	/**
	 * Delete plugin transient.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function deletePluginTransient(string $key) : bool
	{
		$key = $this->applyPrefix($key);
		if ( $this->isMultisite() && $this->allowedMultisite() ) {
			return $this->deleteSiteTransient($key);
		}
		return $this->deleteTransient($key);
	}

	/**
	 * Purge all plugin transients.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function purgePluginTransients() : bool
	{
		$namespace = $this->getNameSpace();
		if ( $this->isMultisite() && $this->allowedMultisite() ) {
			return $this->purgeSiteTransients($namespace);
		}
		return $this->purgeTransients($namespace);
	}

	/**
	 * Get plugin cache value.
	 * [Filter: {plugin}-cache-key].
	 * [Filter: {plugin}-cache-lang].
	 * [Filter: {plugin}-cache-group].
	 * [Filter: {plugin}-get-cache].
	 * [Filter: {plugin}-cache-status].
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getPluginCache($key, ?bool &$status = null, ?string $group = null)
	{
		$key = $this->lowercase(
			$this->applyNamespace($key)
		);
		$key = $this->applyPluginFilter('cache-key', $key);

		if ( $this->hasMultilingual() ) {
			$key = "{$key}-{$this->getLang()}";
			$key = $this->applyPluginFilter('cache-lang', $key);
		}

		if ( $this->isType('null', $group) ) {
			$group = $this->getNamespace();
			$group = $this->applyPluginFilter('cache-group', $group);
		}

		if ( $this->hasPluginFilter('get-cache') ) {
			$status = $this->applyPluginFilter('cache-status', $status, $key);
			return $this->applyPluginFilter('get-cache', $key, $group);
		}

		return $this->getCache($key, $status, $group);
	}

	/**
	 * Set plugin cache value.
	 * [Filter: {plugin}-cache-key].
	 * [Filter: {plugin}-cache-lang].
	 * [Filter: {plugin}-cache-group].
	 * [Filter: {plugin}-set-cache].
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function setPluginCache($key, $value, ?int $ttl = null, ?string $group = null) : bool
	{
		$key = $this->lowercase(
			$this->applyNamespace($key)
		);
		$key = $this->applyPluginFilter('cache-key', $key);

		if ( $this->hasMultilingual() ) {
			$key = "{$key}-{$this->getLang()}";
			$key = $this->applyPluginFilter('cache-lang', $key);
		}

		if ( $this->isType('null', $group) ) {
			$group = $this->getNamespace();
			$group = $this->applyPluginFilter('cache-group', $group);
		}

		if ( $this->isType('null', $ttl) ) {
			$ttl = $this->getExpireIn();
			$ttl = $this->applyPluginFilter('cache-ttl', $ttl);
		}

		if ( $this->hasPluginFilter('set-cache') ) {
			return $this->applyPluginFilter('set-cache', $key, $value, $ttl, $group);
		}

		return $this->setCache($key, $value, $ttl, $group);
	}

	/**
	 * Add value to plugin cache.
	 * [Filter: {plugin}-cache-key].
	 * [Filter: {plugin}-cache-lang].
	 * [Filter: {plugin}-cache-group].
	 * [Filter: {plugin}-add-cache].
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addPluginCache($key, $value, ?int $ttl = null, ?string $group = null) : bool
	{
		$key = $this->lowercase(
			$this->applyNamespace($key)
		);
		$key = $this->applyPluginFilter('cache-key', $key);

		if ( $this->hasMultilingual() ) {
			$key = "{$key}-{$this->getLang()}";
			$key = $this->applyPluginFilter('cache-lang', $key);
		}

		if ( $this->isType('null', $group) ) {
			$group = $this->getNamespace();
			$group = $this->applyPluginFilter('cache-group', $group);
		}
		
		if ( $this->isType('null', $ttl) ) {
			$ttl = $this->getExpireIn();
			$ttl = $this->applyPluginFilter('cache-ttl', $ttl);
		}

		if ( $this->hasPluginFilter('add-cache') ) {
			return $this->applyPluginFilter('add-cache', $key, $value, $ttl, $group);
		}

		return $this->addCache($key, $value, $ttl, $group);
	}

	/**
	 * Update plugin cache value.
	 * [Filter: {plugin}-cache-key].
	 * [Filter: {plugin}-cache-lang].
	 * [Filter: {plugin}-cache-group].
	 * [Filter: {plugin}-update-cache].
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function updatePluginCache($key, $value, ?int $ttl = null, ?string $group = null) : bool
	{
		$key = $this->lowercase(
			$this->applyNamespace($key)
		);
		$key = $this->applyPluginFilter('cache-key', $key);

		if ( $this->hasMultilingual() ) {
			$key = "{$key}-{$this->getLang()}";
			$key = $this->applyPluginFilter('cache-lang', $key);
		}

		if ( $this->isType('null', $group) ) {
			$group = $this->getNamespace();
			$group = $this->applyPluginFilter('cache-group', $group);
		}

		if ( $this->isType('null', $ttl) ) {
			$ttl = $this->getExpireIn();
			$ttl = $this->applyPluginFilter('cache-ttl', $ttl);
		}

		if ( $this->hasPluginFilter('update-cache') ) {
			return $this->applyPluginFilter('update-cache', $key, $value, $ttl, $group);
		}

		return $this->updateCache($key, $value, $ttl, $group);
	}

	/**
	 * Delete plugin cache.
	 * [Filter: {plugin}-cache-key].
	 * [Filter: {plugin}-cache-lang].
	 * [Filter: {plugin}-cache-group].
	 * [Filter: {plugin}-delete-cache].
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function deletePluginCache($key, ?string $group = null) : bool
	{
		$key = $this->lowercase(
			$this->applyNamespace($key)
		);
		$key = $this->applyPluginFilter('cache-key', $key);

		if ( $this->hasMultilingual() ) {
			$key = "{$key}-{$this->getLang()}";
			$key = $this->applyPluginFilter('cache-lang', $key);
		}

		if ( $this->isType('null', $group) ) {
			$group = $this->getNamespace();
			$group = $this->applyPluginFilter('cache-group', $group);
		}

		if ( $this->hasPluginFilter('delete-cache') ) {
			return $this->applyPluginFilter('delete-cache', $key, $group);
		}

		return $this->deleteCache($key, $group);
	}

	/**
	 * Purge plugin cache.
	 * [Filter: {plugin}-purge-cache].
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function purgePluginCache() : bool
	{
		if ( $this->hasPluginFilter('purge-cache') ) {
			return $this->applyPluginFilter('purge-cache', false);
		}
		return $this->purgeCache();
	}

	/**
	 * Add plugin dashboard widget.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addPluginWidget($cb, ?string $name = null)
	{
		if ( !$this->hasPluginCap() ) {
			return;
		}
		
		if ( !$name ) {
			$name = $this->getPluginName();
		}

		$id = $this->applyNamespace(
			$this->slugify($name)
		);
		$id = "{$id}-widget";

		$name = $this->translate($name);
		$name = "{$this->getPluginName()} : {$name}";

		$this->addDashboardWidget($id, $name, $cb);
	}

	/**
	 * Load plugin translation (Overridden).
	 * [Action: init].
	 * [Filter: {plugin}-translation-path].
	 *
	 * @access public
	 * @return void
	 */
	public function localize()
	{
		$domain   = $this->getNameSpace();
		$path     = $this->applyNamespace('languages', '/');
		$override = $this->getThemeDir($path);
		$override = $this->applyPluginFilter('translation-path', $override);

        if ( $this->isDir($override) ) {
			$file = $this->parseTranslationFile($domain);
			$file = "{$override}{$file}";
			if ( $this->isFile($file) ) {
				$this->loadTranslation($domain, $file);
			}

        } else {
        	$this->loadPluginTranslation($domain, $path);
        }
	}

	/**
	 * Translate deep strings.
	 *
	 * @access public
	 * @param array $strings
	 * @return array
	 */
	public function translateDeep(array $strings) : array
	{
		$this->recursiveArray($strings, function(&$string) {
			if ( $this->isType('string', $string) ) {
				$string = $this->translate($string);
			}
		});
		return $strings;
	}

	/**
	 * Load plugin translated strings,
	 * Admin and Front.
	 *
	 * @access public
	 * @param string $type
	 * @return array
	 */
	public function loadStrings(?string $type = null) : array
	{
		$strings = $this->getStrings();
		switch ($type) {
			case 'admin':
				return $this->translateDeep(
					$strings['admin']
				);
				break;

			case 'front':
				return $this->translateDeep(
					$strings['front']
				);
				break;
		}
		return $this->translateDeep($strings);
	}
	
	/**
	 * Translate string,
	 * May require quotes escaping.
	 *
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public function translate(string $string) : string
	{
		return __($string, $this->getNameSpace());
	}

	/**
	 * Translate string with variables,
	 * May require quotes escaping.
	 *
	 * @access public
	 * @param string $string
	 * @param mixed $vars
	 * @return string
	 */
	public function translateVar(string $string, $vars = null) : string
	{
		if ( $this->isType('array', $vars) ) {
			return vsprintf($this->translate($string), $vars);
		}
		if ( $this->isType('string', $vars) ) {
			$vars = $this->replaceString('/\s+/', $this->translate('{Empty}'), $vars, true);
			$string = $this->replaceString($vars, '%s', $string);
			return sprintf($this->translate($string), $vars);
		}
		return $string;
	}

	/**
	 * Translate string (Alias).
	 *
	 * @access public
	 * @param mixed $string
	 * @return string
	 */
	public function trans(?string $string) : string
	{
		return $this->translate((string)$string);
	}

	/**
	 * Translate string with variables (Alias).
	 *
	 * @access public
	 * @param string $string
	 * @param mixed $vars
	 * @return string
	 */
	public function transVar(?string $string, $vars = null) : string
	{
		return $this->translateVar((string)$string, $vars);
	}

	/**
	 * Set HTTP response,
	 * Including translated message.
	 *
	 * @access protected
	 * @param mixed $msg
	 * @param mixed $content
	 * @param string $status
	 * @param int $code
	 * @return void
	 */
	protected function setResponse($msg, $content = [], string $status = 'success', int $code = 200)
	{
		if ( $this->isType('array', $msg) && count($msg) == 2 ) {
		  	$temp = $msg[0];
		  	$args = (array)$msg[1];
		  	$msg  = $this->transVar($temp, $args);
		  	
		} else {
			$msg = $this->translate($msg);
		}
		$this->setHttpResponse($msg, $content, $status, $code);
	}
}
