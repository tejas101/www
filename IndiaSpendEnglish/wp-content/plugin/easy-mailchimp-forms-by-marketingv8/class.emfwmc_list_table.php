<?php

	// This is the representation of the MailChimp's List in the general settings page
	if (!class_exists('WP_List_Table')) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}
	
	class EMFWMC_List_Table extends WP_List_Table {
		
		private $table_data;
		
		function __construct($mydata) {
			parent::__construct( array(
				'singular'=> __('list', 'emfw_language_domain'), //Singular label
				'plural' => __('lists', 'emfw_language_domain'), //plural label, also this well be one of the table css class
				'ajax'   => false // We won't support Ajax for this table
			) );
			$this->table_data = $mydata;
		}
				
		// Add extra markup in the toolbars before or after the list
		function extra_tablenav($which) {
			if ($which == "top") {
				//The code that goes before the table is here
				echo __('The following table displays all of the list you have. To edit settings please click one of the list name.', 'emfw_language_domain');
			}
			if ($which == "bottom") {
				//The code that goes after the table is there
			}
		}
		
		// Define the columns that are going to be used in the table
		function get_columns() {
			return $columns= array(
				'col_link_listname' => __('List name', 'emfw_language_domain'),
				'col_link_isdoubleoption' => __('Double opt-in', 'emfw_language_domain'),
				'col_link_hideform' => __('Hide after subscription', 'emfw_language_domain'),
				'col_link_redirurl' => __('Redirect URL', 'emfw_language_domain'),
			);
		}
		
		// Decide which columns to activate the sorting functionality on
		public function get_sortable_columns() {
			return $sortable = array(
				'col_link_listname' => array('link_listname', false),
				'col_link_isdoubleoption' => array('link_isdoubleoption', false),
				'col_link_hideform' => array('link_hideform', false),
				'col_link_redirurl' => array('link_redirurl', false),
			);
		}
		
		// Sorting array
		private function array_sort($array, $on, $order = "ASC") {
			$new_array = array();
			$sortable_array = array();

			if (count($array) > 0) {
				foreach ($array as $k => $v) {
					if (is_array($v)) {
						foreach ($v as $k2 => $v2) {
							if ($k2 == $on) {
    						$sortable_array[$k] = $v2;
							}
						}
					} else {
						$sortable_array[$k] = $v;
					}
				}

				switch ($order) {
					case "ASC": asort($sortable_array); break;
					case "DESC": arsort($sortable_array); break;
				}

				foreach ($sortable_array as $k => $v) {
					$new_array[$k] = $array[$k];
				}
			}

			return $new_array;
		}
		
		// Prepare the table with different parameters, pagination, columns and table elements
		function prepare_items() {
			$perpage = 5; // How many to display per page?
			$columns = $this->get_columns();
      $hidden = array();
      $sortable = $this->get_sortable_columns();
      
      $this->_column_headers = array($columns, $hidden, $sortable);
      $data = $this->table_data;
      
      function usort_reorder($a,$b){
				$orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'title'; //If no sort, default to title
				$order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
				$result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
				return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
			}
			usort($data, 'usort_reorder');
			
			$current_page = $this->get_pagenum();
			$total_items = count($data);
			$data = array_slice($data, (($current_page - 1) * $per_page), $per_page);
			$this->items = $data;
			$this->set_pagination_args( array(
				'total_items' => $total_items,                  
				'per_page'    => $per_page,                     
				'total_pages' => ceil($total_items / $per_page)
			) );
		}
		
		
		// Display the rows of records in the table
		function display_rows() {
			// Get the records registered in the prepare_items method
			
			$records = $this->items;

			// Get the columns registered in the get_columns and get_sortable_columns methods
			//list( $columns, $hidden ) = $this->get_column_info();
			$columns = $this->get_columns();
			$hidden = array();
			
			// Loop for each record
			if (!empty($records)) {
				for ($i = 0; $i < count($records); $i++) {
					echo '<tr id="record_' . $records[$i]['link_id'] . '">';
					foreach ( $columns as $column_name => $column_display_name ) {
						$class = "class='" . $column_name ." column-" . $column_name . "'";
						$style = "";
						if (in_array($column_name, $hidden)) $style = ' style="display:none;"';
						$attributes = $class . $style;
						$editlink  = '#TB_inline?width=600&height=550&inlineId=icid_' . $records[$i]['link_id'];
						switch ($column_name) {							
							case 'col_link_listname': echo '<td ' . $attributes . '><a title="Edit ' . stripslashes($records[$i]['link_listname']) . ' Settings" class="thickbox" href="' . $editlink . '">' . stripslashes($records[$i]['link_listname']) . '</a></td>'; break;
							case 'col_link_isdoubleoption': 
								echo '<td ' . $attributes . '>';
								if ($records[$i]['link_isdoubleoption']) echo 'true';
								else echo 'false';
								echo '</td>'; 
								break;
							case 'col_link_hideform': 
								echo '<td ' . $attributes . '>';
								if ($records[$i]['link_hideform']) echo 'true';
								else echo 'false';
								echo '</td>';
								break;
							case 'col_link_redirurl': echo '<td ' . $attributes . '>' . stripslashes($records[$i]['link_redirurl']) . '</td>'; break;
						}
					}
					echo '</tr>';
				}
			}
		}
		
	}
	
?>