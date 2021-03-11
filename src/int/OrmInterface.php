<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.4.3
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

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
     * @param string $format false
     * @return bool
     */
	function insert($table, $data = [], $format = false);

    /**
     * @param string $table
     * @param array $where
     * @param string $format false
     * @return bool
     */
	function delete($table, $where = [], $format = null);

    /**
     * @param string $table
     * @return void
     */
	function deleteAll($table);
}
