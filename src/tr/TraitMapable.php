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

namespace VanillePlugin\tr;

use VanillePlugin\inc\Arrayify;

trait TraitMapable
{
    /**
     * @access protected
     * @inheritdoc
     */
    protected function mapArray($callback, array $array, ?array $arrays = null)
    {
        switch ($callback) {
            case 'values':
                $callback = 'array_values';
                break;
        }
        return Arrayify::map($callback, $array, $arrays);
    }

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function recursiveArray(&$array, $callback, $arg = null) : bool
    {
        return Arrayify::recursive($array, $callback, $arg);
    }
}
