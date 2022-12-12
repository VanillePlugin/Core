<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.3
 * @copyright : (c) 2018 - 2023 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\int;

interface OrmInterface
{
    /**
     * @param OrmQueryInterface $data
     * @return mixed
     */
    function select(OrmQueryInterface $data);

    /**
     * @param OrmQueryInterface $data
     * @return int
     */
	function count(OrmQueryInterface $data);

    /**
     * @param string $table
     * @param array $data
     * @param string $format
     * @return bool
     */
	function insert($table, $data = [], $format = false);

    /**
     * @param string $table
     * @param array $where
     * @param string $format
     * @return bool
     */
	function delete($table, $where = [], $format = null);

    /**
     * @param string $table
     * @param bool $resetId
     * @return void
     */
	function deleteAll($table, $resetId = true);
}
