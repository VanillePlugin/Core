<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

if ( !TypeCheck::isFunction('\WP_List_Table') ) {
	require_once Globals::rootDir('wp-admin/includes/class-wp-list-table.php');
}

use \WP_List_Table as Core;

class Table extends Core
{
	/**
	 * @access protected
	 * @var string $type
	 * @var int $limit
	 * @var bool $pagination
	 * @var array $data
	 * @var array $columns
	 * @var array $sortable
	 * @var array $hidden
	 */
	protected $type;
	protected $limit;
	protected $pagination = true;
	protected $data = [];
	protected $columns = [];
	protected $sortable = [];
	protected $hidden = [];

	/**
	 * Setup data table.
	 *
	 * @param array $args
	 */
	public function __construct(array $args = [])
	{
		$conf = [];
		$args = Arrayify::merge([
			'type'  => null,
			'limit' => 10,
			'ajax'  => false
		], $args);

		$this->limit = $args['limit'];

		if ( $args['type'] ) {
			$this->type = $args['type'];
			$conf = [
				'singular' => "{$args['type']}",
				'plural'   => "{$args['type']}s"
			];
		}

		parent::__construct($conf);
	}

	/**
	 * Set data.
	 *
	 * @access public
	 * @param array $data
	 * @param array $columns
	 * @return object
	 */
	public function setData(array $data = [], array $columns = []) : self
	{
		$this->data = $data;
		$this->columns = $columns;
		return $this;
	}

	/**
	 * Hide columns.
	 *
	 * @access public
	 * @param array $hidden
	 * @return object
	 */
	public function hide(array $hidden = []) : self
	{
		$this->hidden = $hidden;
		return $this;
	}

	/**
	 * Sort data.
	 *
	 * @access public
	 * @param array $sortable
	 * @return object
	 */
	public function sort(array $sortable = []) : self
	{
		$this->sortable = $sortable;
		return $this;
	}

	/**
	 * Disable pagination.
	 *
	 * @access public
	 * @return object
	 */
	public function noPagination() : self
	{
		$this->pagination = false;
		return $this;
	}

	/**
	 * Render table.
	 *
	 * @access public
	 * @param bool $search
	 * @return void
	 */
	public function render(bool $search = false)
	{
		$this->openForm();
		$this->prepare_items();
		if ( $search ) {
			$this->search_box('Search', $this->id);
		}
		$this->display();
		$this->closeForm();
	}

	/**
	 * @inheritdoc
	 */
	public function get_columns() : array
	{
		return $this->columns;
	}

	/**
	 * @inheritdoc
	 */
	public function getColumns() : array
	{
		return $this->get_columns();
	}

	/**
	 * @inheritdoc
	 */
	public function get_sortable_columns() : array
	{
		foreach ($this->sortable as $key => $value) {
			if ( TypeCheck::isInt($key) ) {
				$this->sortable[$value] = "{$value}";
				unset($this->sortable[$key]);
			}
		}
		return $this->sortable;
	}

	/**
	 * @inheritdoc
	 */
	public function getSortableColumns() : array
	{
		return $this->get_sortable_columns();
	}

	/**
	 * @inheritdoc
	 */
	public function get_hidden_columns() : array
	{
		return $this->hidden;
	}

	/**
	 * @inheritdoc
	 */
	public function getHiddenColumns() : array
	{
		return $this->get_hidden_columns();
	}

	/**
	 * @inheritdoc
	 */
	public function column_default($item, $name)
	{
	    return $item[$name];
	}

	/**
	 * @inheritdoc
	 */
	public function columnDefault($item, $name)
	{
	    return $this->column_default($item, $name);
	}

	/**
	 * @inheritdoc
	 */
	public function prepare_items()
	{
		// Set pagination
		if ( $this->pagination == true ) {

			$current = $this->get_pagenum();
			$total   = count($this->data);
			$offset  = (($current - 1) * $this->limit);
			$this->data = Arrayify::slice($this->data, $offset, $this->limit);

			$this->set_pagination_args([
				'total_items' => $total,
				'per_page'    => $this->limit,
				'total_pages' => ceil($total / $this->limit)
			]);
		}

		// Set header
		$this->_column_headers = [
			$this->get_columns(),
			$this->get_hidden_columns(),
			$this->get_sortable_columns()
		];

		// Assing data
		$this->items = $this->data;
	}

	/**
	 * @inheritdoc
	 */
	public function prepareItems()
	{
		$this->prepare_items();
	}

	/**
	 * Open form output.
	 *
	 * @access protected
	 * @return void
	 */
	protected function openForm()
	{
		$type = ($this->type) ? $this->type : 'data';
		$page = HttpRequest::get('page');
	    $output  = '<div class="table-wrap">';
	    $output .= '<form id="' . $type . '-filter" method="GET">';
	    $output .= '<input type="hidden" name="page" value="' . $page . '">';
	    echo $output;
	}

	/**
	 * Close form output.
	 *
	 * @access protected
	 * @return void
	 */
	protected function closeForm()
	{
	    $output  = '</form>';
	    $output .= '</div>';
	    echo $output;
	}
}
