<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.7
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 */

// Init WP Core
global $_composer_bin_dir;
$root = dirname(
    dirname(
        dirname(
            dirname(
                dirname(
                    dirname($_composer_bin_dir)
                )
            )
        )
    )
);
include("{$root}/wp-load.php");