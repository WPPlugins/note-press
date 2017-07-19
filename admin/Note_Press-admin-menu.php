<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.datainterlock.com
 * @since      1.0.0
 *
 * @package    Notepress2
 * @subpackage Notepress2/admin/partials
 
 */

/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Note_Press
 * @author    datainterlock <postmaster@datainterlock.com>
 * @license   GPL-3.0+
 * @link      http://www.datainterlock.com
 * @Copyright (C) 2015 Rod Kinnison postmaster@datainterlock.com
 */

if (!defined('WPINC'))
	{
	die;
	}
echo '<div class="wrap">';
echo '<table width="100%" cellpadding="5">';
echo '<tr><td width="100px"><img src="' . plugin_dir_url(__FILE__) . 'images/NPLogo1.png" align="bottom" hspace="3" width="100" height="97" /></td>';
echo '<td><h2>' . __('Note Press', 'Note_Press') . '</h2>';
echo '<p>' . __('For more information and instructions please visit our website at: ', 'Note_Press') . '<a href="http://www.datainterlock.com" target="_blank">http://www.datainterlock.com</a>
</td></tr></table><hr />';
if (!class_exists('WP_List_Table'))
	{
	require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
	}
class Note_Press_List_Table extends WP_List_Table
	{
	var $example_data = array();
	function __construct()
		{
		global $status, $page;
		parent::__construct(array(
			'singular' => __('note', 'mylisttable'),
			'plural' => __('notes', 'mylisttable'),
			'ajax' => false
		));
		}
	function column_default($item, $column_name)
		{
		switch ($column_name)
		{
			case 'icon':
			case 'title':
			case 'addedby':
			case 'datetime':
			case 'priority':
			case 'deadline':
				return $item[$column_name];
			default:
				return print_r($item, true);
		}
		}
	function column_title($item)
		{
		$actions = array(
			'view' => sprintf('<a href="?page=%s&action=%s&id=%s">' . __('View', 'Note_Press') . '</a>', $_REQUEST['page'], 'view', $item['ID']),
			'edit' => sprintf('<a href="?page=%s&action=%s&id=%s">' . __('Edit', 'Note_Press') . '</a>', $_REQUEST['page'], 'edit', $item['ID']),
			'delete' => sprintf('<a href="?page=%s&action=%s&id=%s" onclick="return confirm(\'' . __('Are you sure you want to delete this note?', 'Note_Press') . '\')">' . __('Delete', 'Note_Press') . '</a>', $_REQUEST['page'], 'delete', $item['ID'])
		);
		return sprintf('%1$s %2$s', $item['title'], $this->row_actions($actions));
		}
	function get_bulk_actions()
		{
		$actions = array(
			'delete' => __('Delete', 'Note_Press')
		);
		return $actions;
		}
	function column_cb($item)
		{
		return sprintf('<input type="checkbox" name="id[]" value="%s" />', $item['ID']);
		}
	function get_columns()
		{
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'icon' => __('Icon', 'Note_Press'),
			'title' => __('Title', 'Note_Press'),
			'addedby' => __('By', 'Note_Press'),
			'datetime' => __('Date', 'Note_Press'),
			'priority' => __('Priority', 'Note_Press'),
			'deadline' => __('Deadline', 'Note_Press')
		);
		return $columns;
		}
	function get_sortable_columns()
		{
		$sortable_columns = array(
			'title' => array(
				'title',
				false
			),
			'addedby' => array(
				'addedby',
				false
			),
			'datetime' => array(
				'datetime',
				false
			),
			'priority' => array(
				'priority',
				false
			),
			'deadline' => array(
				'deadline',
				false
			)
		);
		return $sortable_columns;
		}
	function get_items($column = 'title', $order = 'DESC')
		{
		global $wpdb;
	
		$table_name = $wpdb->prefix . "Note_Press";
		switch ($order)
		{
			case 'asc':
				$order = 'ASC';
				break;
			case 'desc':
				$order = 'DESC';
				break;
			case 'ASC':
				$order = 'ASC';
				break;
			case 'DESC':
				$order = 'ASC';
				break;
			default:
				$order = 'DESC';
		}
		switch ($column)
		{
			case 'title':
				$column = 'Title';
				break;
			case 'addedby':
				$column = 'AddedBy';
				break;
			case 'datetime':
				$column = 'Date';
				break;
			case 'priority':
				$column = 'Priority';
				break;
			case 'deadline':
				$column = 'Deadline';
				break;
			default:
				$column = 'Title';
		}
		if (isset($_GET['s']))
			{
			$myfilter = " where Title LIKE '%%%s%%' or Content LIKE '%%%s%%'";
			$SQL = $wpdb->prepare("SELECT * FROM $table_name" . $myfilter . " order by $column $order",
			$_GET['s'],$_GET['s']);
			}
		else
			{
			$SQL = "SELECT * FROM $table_name order by $column $order";
			}
		$mylink = $wpdb->get_results($SQL);
		return $mylink;
		}
	function prepare_items()
		{
		$_SERVER['REQUEST_URI'] = remove_query_arg('_wp_http_referer', $_SERVER['REQUEST_URI']);
		if (isset($_GET['orderby']) && isset($_GET['order']))
			{
			$orderby = $_GET['orderby'];
			$order   = $_GET['order'];
			}
		else
			{
			$orderby = 'title';
			$order   = 'ASC';
			}
		$mylink = $this->get_items($orderby, $order);
		foreach ($mylink as $link)
			{
			$users = get_users(array(
				'fields' => array(
					'display_name',
					'ID'
				)
			));
			foreach ($users as $user)
				{
				if ($user->ID == $link->AddedBy)
					{
					$username = $user->display_name;
					}
				}
			$iconpath = get_option("Note_Press_icons_url") . $link->Icon;
			switch ($link->Priority)
			{
				case 0:
					$picon = "<img src=" . plugins_url('admin/images/P0.png', dirname(__FILE__)) . " alt='Icon not found'>";
					break;
				case 1:
					$picon = "<img src=" . plugins_url('admin/images/P1.png', dirname(__FILE__)) . " alt='Icon not found'>";
					break;
				case 2:
					$picon = "<img src=" . plugins_url('admin/images/P2.png', dirname(__FILE__)) . " alt='Icon not found'>";
					break;
			}
			if ($link->Deadline == NULL)
				{
				$thisdate = '';
				}
			else
				{
				$date     = new DateTime($link->Deadline);
				$thisdate = date_format($date, 'Y-m-d');
				}
			$example_data[] = array(
				'ID' => $link->ID,
				'icon' => '<img src="' . $iconpath . '" width="16" height="16" />',
				'title' => '<a href="?page=Note_Press-Main-Menu&action=view&id=' . $link->ID . '">' . $link->Title . '</a>',
				'addedby' => $username,
				'datetime' => $link->Date,
				'priority' => $picon,
				'deadline' => $thisdate
			);
			}
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array(
			$columns,
			$hidden,
			$sortable
		);
		$per_page              = 5;
		$current_page          = $this->get_pagenum();
		$total_items           = count(@$example_data);
		if ($total_items > 0)
			{
			$example_data = array_slice($example_data, (($current_page - 1) * $per_page), $per_page);
			}
		$this->set_pagination_args(array(
			'total_items' => $total_items,
			'per_page' => $per_page
		));
		$this->items = @$example_data;
		}
	}
function Note_Press_render_list_page()
	{
	$myListTable = new Note_Press_List_Table();
	$myListTable->prepare_items();
	echo '</pre><div class="wrap"><h3>' . __('Notes', 'Note_Press') . '</h3>';
	echo '<form method="get">';
	echo '<input type="hidden" name="page" value="' . $_REQUEST['page'] . '" />' . $myListTable->search_box(__("Search", "Note_Press"), 'search_id');
	echo '</form>';
	echo '<form id="events-filter" method="get">';
	echo '<input type="hidden" name="page" value="' . $_REQUEST['page'] . '" />';
	$myListTable->display();
	echo '</form>';
	echo '</div>';
	}
function Note_PressshowMessage($message, $errormsg = false)
	{
	if ($errormsg)
		{
		echo '<div id="message" class="error">';
		}
	else
		{
		echo '<div id="message" class="updated fade">';
		}
	echo "<p><strong>$message</strong></p></div>";
	}
function Note_Pressshow_menu()
	{
	echo '<form action="" method="get">';
	echo '<button class="button-primary" type="submit" name="action" value="Add">' . __('Add A Note', 'Note_Press') . '</button>';
	echo '<input name="page" type="hidden" value="Note_Press-Main-Menu" />';
	echo '</form><hr>';
	}
function Note_Pressget_notes()
	{
	global $wpdb;
	Note_Pressshow_menu();
	Note_Press_render_list_page();
	}
function Note_Pressshow_note($which)
	{
	global $wpdb;
	$table_name = $wpdb->prefix . "Note_Press";
	$mylink     = $wpdb->get_results("SELECT * FROM $table_name where ID=$which");
	$wpdb->update($table_name, array(
		'userRead' => '1'
	), array(
		'ID' => $which
	), array(
		'%s'
	), array(
		'%d'
	));
	if (!$mylink)
		{
		Note_PressshowMessage(__('That note was not found.', 'Note_Press'), true);
		Note_Pressget_notes();
		}
	else
		{
		foreach ($mylink as $link)
			{
			$users = get_users(array(
				'fields' => array(
					'display_name',
					'ID'
				)
			));
			foreach ($users as $user)
				{
				if ($user->ID == $link->AddedBy)
					{
					$username = $user->display_name;
					}
				}				
			echo '<form action="" method="get">';
			echo '<input name="page" type="hidden" value="Note_Press-Main-Menu" />';
			echo '<button class="button-primary" type="submit" name="edit" value="' . $link->ID . '">' . __('Edit This Note', 'Note_Press') . '</button>';
			echo '&nbsp;';
			echo '<button class="button-primary" type="submit" name="action" value="List">' . __('Back to List', 'Note_Press') . '</button>';
			echo '</form><hr>';
			$iconpath = get_option("Note_Press_icons_url") . $link->Icon;
			echo '<table class="form-table widefat ltr" border="1" width="100%">';
			echo "<tr><td><h3><img src='$iconpath' width='16' height='16' />&nbsp;&nbsp;" . __('Title:', 'Note_Press') . " $link->Title</h3></td></tr>";
			echo "<tr><td><p><strong>" . __('Date Added/Last Edited:', 'Note_Press') . "</strong> $link->Date</p></td></tr>";
			echo "<tr><td><p><strong>" . __('Added By:', 'Note_Press') . "</strong> $username</p></td></tr>";
			switch ($link->Priority)
			{
				case 0:
					$picon = "<img src=" . plugins_url('admin/images/P0.png', dirname(__FILE__)) . " alt='Icon not found'>";
					break;
				case 1:
					$picon = "<img src=" . plugins_url('admin/images/P1.png', dirname(__FILE__)) . " alt='Icon not found'>";
					break;
				case 2:
					$picon = "<img src=" . plugins_url('admin/images/P2.png', dirname(__FILE__)) . " alt='Icon not found'>";
					break;
			}
			echo "<tr><td><p><strong>" . __('Priority:', 'Note_Press') . "</strong> $picon</p></td></tr>";
			if ($link->Deadline == NULL)
				{
				$thisdate = '';
				}
			else
				{
				$date     = new DateTime($link->Deadline);
				$thisdate = date_format($date, 'Y-m-d');
				}
			echo "<tr><td><p><strong>" . __('Deadline:', 'Note_Press') . "</strong> $thisdate</p></td></tr>";
			$content = do_shortcode(nl2br($link->Content));
			echo "<tr><td><p><strong>" . __('Contents:', 'Note_Press') . "</strong></p><hr><p> $content</p></td></tr>";
			echo '</table>';
			}
		}
	}
function Note_Pressadd_note($which = -1)
	{
	global $wpdb, $current_user;
	if ($which <> -1)
		{
		$table_name = $wpdb->prefix . "Note_Press";
		$mylink     = $wpdb->get_results("SELECT * FROM $table_name where ID=$which");
		if (!$mylink)
			{
			Note_PressshowMessage(__('That note was not found.', 'Note_Press'), true);
			Note_Pressget_notes();
			exit;
			}
		}
	echo '<form action="" method="get">';
	echo '<input name="page" type="hidden" value="Note_Press-Main-Menu" />';
	echo '<button class="button-primary" type="submit" name="List" value="List">' . __('Back to List', 'Note_Press') . '</button>';
	echo '<hr>';
	echo '</form>';
	echo '<form action="" method="post">';
	echo '<div id="poststuff">

			<div id="post-body" class="metabox-holder columns-2">

				<div id="post-body-content">';
	echo '			<div id="namediv" class="stuffbox">';
	echo '				<h3><label for="Title">' . __('Title:', 'Note_Press') . '</label></h3>';
	echo '				<div class="inside">';
	echo '					<input name="Title" type="text" id="Title" size="100" maxlength="255" value="';
	if ($which <> -1)
		{
		echo $mylink[0]->Title;
		}
	echo '" required>

							<p>' . __('Enter a title for this note.', 'Note_Press') . '</p>';
	echo '				</div>';
	echo '			</div>';
	$thisdate = '';
	if ($which <> -1)
		{
		if ($mylink[0]->Deadline == NULL)
			{
			$thisdate = '';
			}
		else
			{
			$date     = new DateTime($mylink[0]->Deadline);
			$thisdate = date_format($date, 'Y-m-d');
			}
		}
	echo '			<div id="namediv" class="stuffbox">';
	echo '				<h3><label for="Deadline">' . __('Deadline:', 'Note_Press') . '</label></h3>';
	echo '				<div class="inside">';
	echo '					<input name="Deadline" type="date" id="Date" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value="' . $thisdate . '">';
	echo '					<p>' . __('Enter a deadline for this note or leave this field blank for no deadline.</br> <strong>Not all browswers support a date picker</strong>. If you do not have the option to select a date, please enter one in the format MM/DD/YYYY.', 'Note_Press') . '</p>';
	echo '				</div>';
	echo '			</div>';
	$checked1 = '';
	$checked2 = '';
	$checked3 = '';
	if ($which <> -1)
		{
		switch ($mylink[0]->Priority)
		{
			case 0:
				$checked1 = 'selected="selected"';
				break;
			case 1:
				$checked2 = 'selected="selected"';
				break;
			case 2:
				$checked3 = 'selected="selected"';
				break;
		}
		}
	echo '			<div id="namediv" class="stuffbox">';
	echo '				<h3><label for="Priority">' . __('Priority:', 'Note_Press') . '</label></h3>';
	echo '				<div class="inside">';
	echo '					
							<select name="Priority">
							<option value=0 style="background-image:url(' . plugins_url('admin/images/P0.png', dirname(__FILE__)) . ')" ' . $checked1 . '>' . __("Low", "Note_Press") . '</option>
							<option value=1 style="background-image:url(' . plugins_url('admin/images/P1.png', dirname(__FILE__)) . ')" ' . $checked2 . '>' . __("Medium", "Note_Press") . '</option>
							<option value=2 style="background-image:url(' . plugins_url('admin/images/P2.png', dirname(__FILE__)) . ')" ' . $checked3 . '>' . __("High", "Note_Press") . '</option>
							</select> ';
	echo '					<p>' . __('Select a priority for this note.', 'Note_Press') . '</p>';
	echo '				</div>';
	echo '			</div>';
	echo '			<div id="icondiv" class="stuffbox">';
	echo '				<h3><label for="Icon">' . __('Icon:', 'Note_Press') . '</label></h3>';
	echo '				<div class="inside">';
	$count = 0;
	if ($which <> -1)
		{
		$blank = false;
		}
	else
		{
		$blank = true;
		}
	echo '<table width="100%" border="0">';
	$path  = get_option("Note_Press_icons_path");
	$files = scandir($path);
	foreach ($files as $myfile)
		{
		if ($myfile <> '.' && $myfile <> '..')
			{
			if ($count == 0)
				{
				echo '<tr>';
				$enddone = FALSE;
				}
			$iconpath = get_option("Note_Press_icons_url") . $myfile;
			echo '<td><input type="radio" name="iconselect[]" value="' . $myfile . '"';
			if ($blank)
				{
				echo ' checked ';
				$blank = false;
				}
			if ($which <> -1)
				{
				if ($mylink[0]->Icon == $myfile)
					{
					echo ' checked ';
					}
				}
			echo '><img src="' . $iconpath . '" width="16" height="16"/></td>';
			$count++;
			if ($count == 15)
				{
				$count = 0;
				echo '</tr>';
				$enddone = TRUE;
				}
			}
		}
	if (!$enddone)
		{
		echo '</tr>';
		}
	echo '</table>';
	echo '<p>' . __('Select an icon for this note.', 'Note_Press') . '</p>';
	echo '				</div>';
	echo '			</div>';
	echo '			<div id="authordiv" class="stuffbox">';
	$users = get_users(array(
		'fields' => array(
			'display_name',
			'ID'
		)
	));
	foreach ($users as $user)
		{
			if ($which <> -1)
			{	
				if ($user->ID == $mylink[0]->AddedBy)
				{
					$username = $user->display_name;
				}
			}
			else
			{
				if ($user->ID == $current_user->ID)
				{
					$username = $user->display_name;
				}						
			}
		}
	echo '			<h3><label for="Author">' . __('Author/Editor: ', 'Note_Press') . $username . '</label></h3>';
	echo '	  		<input name="display_name" type="hidden" value="' . $username . '" />';
	echo '			</div>';
	echo '			<div id="namediv" class="stuffbox">';
	echo '				<h3><label for="Note">' . __('Contents:', 'Note_Press') . '</label></h3>';
	echo '				<div class="inside">';
	if ($which <> -1)
		{
		$content = $mylink[0]->Content;
		}
	else
		{
		$content = '';
		}
	$editor_id = 'Note_Presseditor';
	wp_editor($content, $editor_id);
	echo '				</div>';
	echo '			</div>';
	echo '		</div>';
	echo '
				<div id="postbox-container-1" class="postbox-container">
					<div id="side-sortables" class="meta-box-sortables ui-sortable">
						<div id="linksubmitdiv" class="postbox ">
						<h3 class="hndle ui-sortable-handle"><span>' . __('Save', 'Note_Press') . '</span></h3>
							<div class="inside">
								<div class="submitbox" id="submitlink">
									<div id="major-publishing-actions">					
										<div id="publishing-action">';
	if ($which <> -1)
		{
		echo '<button  class="button-primary" type="submit" name="Update" value="' . $mylink[0]->ID . '">' . __('Update Note', 'Note_Press') . '</button>';
		}
	else
		{
		echo '<input name="DoAdd" type="submit" class="button-large button-primary" id="publish" accesskey="p" value="' . __('Add Note', 'Note_Press') . '">';
		}
	echo '								</div>
										<div class="clear">
										</div>
									</div>
									<div class="clear">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>';
	echo '	</div>';
	echo '</div>';
	echo '<div id="clear"></div>';
	echo '</form>';
	}
function Note_Pressupdate_note($thisid)
	{
	global $wpdb;
	if (stripslashes_deep($_POST['Title']) == '')
		{
		Note_PressshowMessage(__('A note must have a title.', 'Note_Press'), true);
		Note_Pressget_notes();
		exit;
		}
	$table_name = $wpdb->prefix . "Note_Press";
	$mylink     = $wpdb->get_results("SELECT * FROM $table_name where ID=$thisid");
	if (!$mylink)
		{
		Note_PressshowMessage(__('That note was not found.', 'Note_Press'), true);
		}
	else
		{
		if (!empty($_POST['Deadline']))
			{
			$thisdate = date("Y-m-d H:i:s", strtotime($_POST['Deadline']));
			}
		else
			{
			$thisdate = NULL;
			}
		$wpdb->update($table_name, array(
			'Icon' => $_POST['iconselect'][0],
			'Title' => stripslashes_deep($_POST['Title']),
			'AddedBy' => get_current_user_id(),
			'Content' => stripslashes_deep($_POST["Note_Presseditor"]),
			'Date' => date("Y-m-d H:i:s"),
			'Deadline' => $thisdate,
			'Priority' => $_POST['Priority'],
			'userTo' => get_current_user_id()
		), array(
			'ID' => $_POST['Update']
		));
		Note_PressshowMessage($_POST['Title'] . ' updated.');
		}
	Note_Pressget_notes();
	}
function Note_Pressinsert_note()
	{
	global $wpdb;
	if (stripslashes_deep($_POST['Title']) == '')
		{
		Note_PressshowMessage(__('A note must have a title.', 'Note_Press'), true);
		Note_Pressget_notes();
		exit;
		}
	$table_name = $wpdb->prefix . "Note_Press";
	if (!empty($_POST['Deadline']))
		{
		$thisdate = date("Y-m-d H:i:s", strtotime($_POST['Deadline']));
		}
	else
		{
		$thisdate = NULL;
		}
	$wpdb->insert($table_name, array(
		'Icon' => $_POST['iconselect'][0],
		'Title' => stripslashes_deep($_POST['Title']),
		'AddedBy' => get_current_user_id(),
		'Content' => stripslashes_deep($_POST["Note_Presseditor"]),
		'Date' => date("Y-m-d H:i:s"),
		'Deadline' => $thisdate,
		'Priority' => $_POST['Priority'],
		'userTo' => get_current_user_id()
	));
	Note_PressshowMessage($_POST['Title'] . ' Added.');
	Note_Pressget_notes();
	}
function Note_Pressdelete_multi_note($thisid)
	{
	global $wpdb;
	$table_name = $wpdb->prefix . "Note_Press";
	$mylink     = $wpdb->get_results("SELECT * FROM $table_name where ID=$thisid");
	if (!$mylink)
		{
		}
	else
		{
		$wpdb->delete($table_name, array(
			'ID' => $thisid
		));
		}
	}
function Note_Pressdelete_note($thisid)
	{
	global $wpdb;
	$table_name = $wpdb->prefix . "Note_Press";
	$mylink     = $wpdb->get_results("SELECT * FROM $table_name where ID=$thisid");
	if (!$mylink)
		{
		Note_PressshowMessage(__('That note was not found', 'Note_Press'), true);
		}
	else
		{
		$wpdb->delete($table_name, array(
			'ID' => $thisid
		));
		Note_PressshowMessage(__('Note Deleted!', 'Note_Press'));
		}
	}

function CheckDB()
{
	global $wpdb;
	$tablename = $wpdb->prefix . "Note_Press";
	$SQL = "SHOW COLUMNS FROM $tablename LIKE 'Priority'";
	$wpdb->get_results($SQL);
	if ($wpdb->num_rows == 0)
	{
		Note_PressshowMessage(__('Please deactivate and re-activate Note Press to complete the upgrade.', 'Note_Press'), true);
		return true;
	}
	else
	{
		return false;
	}
}

if (CheckDB())
{
	die();
}
else
{
if (isset($_POST['Update']))
	{
	Note_Pressupdate_note($_POST['Update']);
	}
elseif (isset($_POST['DoAdd']))
	{
	Note_Pressinsert_note();
	}
elseif (isset($_GET['s']))
	{
	Note_Pressget_notes();
	}
elseif (isset($_GET['List']) || isset($_GET['orderby']) && isset($_GET['order']))
	{
	Note_Pressget_notes();
	}
elseif (isset($_GET['edit']))
	{
	Note_Pressadd_note($_GET['edit']);
	}
elseif (@$_GET['action'] == 'Add')
	{
	Note_Pressadd_note();
	}
elseif (isset($_GET['action']))
	{
	if ($_GET['action'] == 'view')
		{
		Note_Pressshow_note($_GET['id']);
		}
	elseif ($_GET['action'] == 'edit')
		{
		Note_Pressadd_note($_GET['id']);
		}
	elseif ($_GET['action'] == 'delete')
		{
		if (is_array($_GET['id']))
			{
			$count = 0;
			foreach ($_GET['id'] as $id)
				{
				Note_Pressdelete_multi_note($id);
				$count++;
				}
			if ($count == 1)
				{
				Note_PressshowMessage($count . __('Note Deleted!', 'Note_Press'));
				}
			else
				{
				Note_PressshowMessage($count . __(' Notes Deleted!', 'Note_Press'));
				}
			}
		elseif (isset($_GET['id']))
			{
			Note_Pressdelete_note($_GET['id']);
			}
		else
			{
			Note_PressshowMessage(__('No notes selected.', 'Note_Press'));
			}
		Note_Pressget_notes();
		}
	else
		{
		Note_Pressget_notes();
		}
	}
else
	{
	Note_Pressget_notes();
	}
}
?>