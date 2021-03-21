<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.4.7
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin;

use Composer\Installer\PackageEvent;

class VanillePluginInstaller
{
    /**
     * @access public
     * @var object $event
     * @return void
     */
    public static function prePackageInstall(PackageEvent $event)
    {
        $package = $event->getOperation()->getPackage();
        var_dump($package);die();
    }
}