<?php 

class Admin extends Admin_Controller {
	
	function index()
	{
		$this->load->library('yaml/sfYaml');
        $this->load->library('yaml/sfYamlDumper');
        $this->load->library('yaml/sfYamlInline');
        $this->load->library('yaml/sfYamlParser');

        $yaml = new sfYamlParser();
        $items = $yaml->parse(file_get_contents(PATH.'/yaml/reservations.yaml'));
        
        $generator = new Resource_Generator($items);
        $generator->create_folders();
        $generator->create_model();
        $generator->create_controller();
        $generator->create_views();
	}

}


class Resource_Generator {
	
	private $_resource;
	private $_relations;
	private $_columns;
	private $_filter_by;
	private $_scope;
	private $_bulk;
	private $_export;
	private $_module_path;

	function __construct($items)
	{
		$this->_resource          = isset($items['resource']) ? $items['resource'] : FALSE;
		$this->_resource_singular = isset($items['resource-singular']) ? $items['resource-singular'] : FALSE;
		$this->_relations         = isset($items['relations']) ? $items['relations'] : FALSE;
		$this->_columns           = isset($items['columns']) ? $items['columns'] : FALSE;
		$this->_filter_by         = isset($items['filter_by']) ? $items['filter_by'] : FALSE;
		$this->_scope             = isset($items['scope']) ? $items['scope'] : FALSE;
		$this->_bulk              = isset($items['bulk']) ? $items['bulk'] : FALSE;
		$this->_export            = isset($items['export']) ? $items['export'] : FALSE;
		$this->_id                = isset($items['id']) ? $items['id'] : FALSE;
		$this->_create_form       = isset($items['create']) ? $items['create'] : FALSE;
	}

	function create_folders()
	{
		$created = array();

		if (is_dir(APPPATH.'modules'))
		{
			$original = $this->_resource;
			$module = strtolower($original);
			$module_path = $this->_module_path = APPPATH.'modules/'.$module;

			$this->_make_dir($module_path);

			$created[] = $this->_make_dir($module_path.'/controllers');
			$created[] = $this->_make_dir($module_path.'/models');
			$created[] = $this->_make_dir($module_path.'/views');
			$created[] = $this->_make_dir($module_path.'/views/menu');
			$created[] = $this->_make_dir($module_path.'/config');
			$created[] = $this->_make_dir($module_path.'/language');
			$created[] = $this->_make_dir($module_path.'/libraries');
			$created[] = $this->_make_dir($module_path.'/helpers');
		}
	}

	private function _make_dir($dir = '')
	{
		if (empty($dir))
		{
			return FALSE;
		}

		if ( ! is_dir($dir))
		{
			mkdir($dir, 0777);
		}
		
		return "$dir created.".br();
	}

	function create_model()
	{
		$resource = ucfirst($this->_resource);

		$relations = array();

		foreach ($this->_relations as $table => $relation)
		{
			$type = (isset($relation['type']) ? '|'.$relation['type'] : '');
			$relations[] = "'$table' => '{$table}.{$relation['primary']} = {$this->_resource}.{$relation['foreign']}{$type}'";
		} 

		$relations = implode(",\n\t\t", $relations);
		
		$select = array();

		foreach ($this->_columns as $col => $label)
		{
			if (strpos($col, '(') !== FALSE)
			{
				$select[] = str_replace(array(
					'(', 
					')'
				), array(
					' as ',
					''					
				), $col);
			}
			else
			{
				$select[] = str_replace('+', ', ', $col);
			}
		}

		$select = implode(', ', $select);

		$model = <<<MODEL
<?php

class {$resource}_Model extends MY_Model {
	
	protected \$table = '$resource';
	protected \$join  = array(
		$relations
	);
	protected \$id    = '$this->_id';
	protected \$select = '$select';

}
MODEL;
		write_file($this->_module_path.'/models/'.strtolower($this->_resource).'_model.php', $model);
	}

	function create_controller()
	{
		$resource = ucfirst($this->_resource);

		$headers = array();

		$cols = array();

		$link_path = 'admin/'.strtolower($this->_resource);

		foreach ($this->_columns as $col => $label)
		{
			if (is_array($label))
			{
				if (isset($label['hidden']))
				{
					continue;
				}

				$headers[] = "anchor('$link_path?order=".strtolower(str_replace(' ', '_', $label['label']))."_'.(\$sort_column == '".strtolower(str_replace(' ', '_', $label['label']))."' && \$sort_order == 'asc' ? 'desc' : 'asc'),'".$label['label']."')";

				if (strpos($col, '(') !== FALSE)
				{
					$col = preg_replace('/.*\(([a-zA-Z0-9-_]+)\)/', 'tabla.$1', $col);
				}

				if (strpos($col, '+') !== FALSE)
				{
					$fields = explode('+', $col);
					$i = 0;

					foreach ($fields as $field)
					{
						$fields[$i] = preg_replace('/([a-zA-Z0-9_-]+\.)+/', '$row->', $field);		
						$i++;			
					}

					$c = implode(".' '.", $fields);
				} 
				else
				{
					$c = preg_replace('/([a-zA-Z0-9_-]+\.)+/', '$row->', $col);
				}

				if (isset($label['as']))
				{
					if ($label['as'] == 'anchor')
					{
						$cols[] = 'anchor("'.$label['href'].'","'.$c.'")';
					}
					else
					{
						$cols[] = $label['as'].'('.$c.')';
					}
				}

				continue;
			}
			$headers[] = "anchor('$link_path?order=".strtolower(str_replace(' ', '_', $label))."_'.(\$sort_column == '".strtolower(str_replace(' ', '_', $label))."' && \$sort_order == 'asc' ? 'desc' : 'asc'),'".$label."')";

			if (strpos($col, '(') !== FALSE)
			{
				$col = preg_replace('/.*\(([a-zA-Z0-9-_]+)\)/', 'tabla.$1', $col);
			}

			if (strpos($col, '+') !== FALSE)
			{
				$fields = explode('+', $col);
				$i = 0;

				foreach ($fields as $field)
				{
					$fields[$i] = preg_replace('/([a-zA-Z0-9_-]+\.)+/', '$row->', $field);		
					$i++;			
				}

				$cols[] = implode(".' '.", $fields);
			} 
			else
			{
				$cols[] = preg_replace('/([a-zA-Z0-9_-]+\.)+/', '$row->', $col);
			}

			$row[] = $col;
		}

		$scopes = array();

		foreach ($this->_scope as $key => $val)
		{
			if (strpos($val, '=>') === FALSE)
			{
				if (strpos($key, '=') !== FALSE)
				{
					list($key, $value) = explode('=', $key);
					$value = trim($value);

					if ( ! is_numeric($value))
					{
						$value = "'$value'";
					}

					$key   = trim($key);
				}

				$incoming_scope = preg_replace('/([a-zA-Z0-9-_]+\.)/', '', $key);

				$scope = <<<SCOPE
case '$incoming_scope':
				\${$this->_resource} = \$this->{$this->_resource}_model->find_by('$key', $value);
				break;
SCOPE;
				$scopes[] = $scope;
			}
		}

		$data = array();
		$models = array();
		$save = array();

		foreach ($this->_create_form as $name => $label)
		{
			if (is_array($label))
			{
				if (isset($label['at']))
				{
					$save[] = "'$name' => {$label['at_var']}";
				}
				else
				{
					$save[] = "'$name' => _post('$name')";
				}

				if (isset($label['model']))
				{
					if (strpos($label['model'], '/') !== FALSE)
					{
						$model_name = preg_replace('/[a-zA-Z0-9_-]+\/([a-zA-Z0-9_-]+)_model/', '$1', $label['model']);
					}
					else
					{
						$model_name = preg_replace('([a-zA-Z0-9_-]+)_model/', '$1', $label['model']);
					}

					$var = strtolower($label['label']);

					$models[] = "
		\$this->load->model('{$label['model']}');
		\${$model_name} = \$this->{$model_name}_model->find_all()->result();
		\$options = array();
		foreach (\${$model_name} as \${$var})
		{
			\$options[\${$var}->{$label['id']}] = \${$var}->{$label['option']};	
		}
		\$data['{$model_name}'] = \$options; 
					";
				}
			}
			else
			{
				$save[] = "'$name' => _post('$name')";
			}
		}

		$filters = array();

		foreach ($this->_filter_by as $field => $label)
		{
			if (strpos($field, '(') !== FALSE)
			{				
				$name = preg_replace('/.*\(([a-zA-Z0-9-_]+)\)/', '$1', $field);
				$field = preg_replace('/\([a-zA-Z0-9-_]+\)/', '', $field);
			}
			else
			{				
				$name = preg_replace('/.*\./', '', $field);
			}

			$filters[] = "
		if (_get('$name'))
		{
			\$filters['$field'] = _get('$name');
		}
			";
		}

		$filters = implode("\n", $filters);


		$headers = implode(", ", $headers);
		$cols 	 = implode(', ', $cols);
		$scopes  = implode("\n", $scopes);
		$models  = implode("\n", $models);
		$save    = implode(",\n\t\t\t", $save);

		$controller = <<<CONTROLLER
<?php

class Admin extends Admin_Controller {

	function index()
	{
		\$this->load->library('table');
		\$this->load->model('{$this->_resource}_model');

		\$this->table->set_template(array(
		    'heading_cell_start'  => '<th class="blue header">',
		    'heading_cell_end'    => '</th>',
		    'table_open'          => '<table class="zebra-striped">',
		));

		\$sort_column = '';
		\$sort_column = '';


		if (_get('order'))
		{
			if (preg_match('/(asc|desc)/i', _get('order')))
			{				
				\$order_type = strrchr(_get('order'), '_');
				\$sort_order = str_replace('_', '', \$order_type);
			}
			\$sort_column = str_replace(\$order_type, '', _get('order'));
			\$this->db->order_by(\$sort_column, \$sort_order);
		}

		switch(\$this->input->get('scope')) 
		{
			$scopes
			default:
				\${$this->_resource} = \$this->{$this->_resource}_model->find_all();
				break;
		}	

		\$filters = array();
		$filters
		if ( ! empty(\$filters))
		{
			\${$this->_resource} = \$this->{$this->_resource}_model->find_all(\$filters);
		}

		\$this->table->set_heading($headers);

		if (\${$this->_resource}->num_rows() > 0)
		{
			foreach (\${$this->_resource}->result() as \$row)
			{
				\$this->table->add_row($cols);				
			}			
		}
		else
		{
			\$this->table->add_row('There are no items to show.');
		}
		$models

		\$data['table'] = \$this->table->generate();

		\$this->template->set_content('filters', 'form_filters')
					   ->set_content('main', 'index')
					   ->set_content('menu', 'menu/actions')
					   ->js('tablesorter.min')
					   ->render(\$data);
	}	

	function create()
	{
		$models
		\$this->template->set_content('main', 'form_create')
					   ->set_content('menu', 'menu/actions')
					   ->render(\$data);
	}

	function details(\${$this->_resource_singular} = 0)
	{
		if (\${$this->_resource_singular} == 0)
		{
			show_error('404');
		}
		
		\$this->load->model('{$this->_resource}_model');
		$models

		\${$this->_resource_singular} = \$this->{$this->_resource}_model->find(\${$this->_resource_singular});

		if (\${$this->_resource_singular}->num_rows() == 0)
		{
			show_error('404');
		}

		\$data['{$this->_resource_singular}'] = \${$this->_resource_singular}->row();

		\$this->template->set_content('main', 'details')
					   ->set_content('menu', 'menu/actions')
					   ->render(\$data);
	}

	function save()
	{
		\$this->load->model('{$this->_resource}_model');
		\$saved = \$this->{$this->_resource}_model->save(array(
			'id'  => _post('id'),
			$save
		));

		if ( ! \$saved)
		{
			Message::error('An error has occured during saving the category', 'admin/{$this->_resource}');
		}

		Message::success('The category has been saved', 'admin/{$this->_resource}');
	}

}
CONTROLLER;

		write_file($this->_module_path.'/controllers/admin.php', $controller);
	}

	function create_views()
	{
		$resource = ucfirst($this->_resource);

		$scopes = array();

		foreach ($this->_scope as $key => $val)
		{
			if (strpos($val, '=>') !== FALSE)
			{
				list($label, $default) = explode('=>', $val);
				$scopes[] = anchor('admin/'.$this->_resource.'/', $label);
			}
			else
			{
				if (strpos($key, '=') !== FALSE)
				{
					list($key, $value) = explode('=', $key);
				}
				$scopes[] = anchor('admin/'.$this->_resource.'/index?scope='.preg_replace('/([a-zA-Z0-9-_]+\.)/', '', $key).'', $val);
			}
		}

		$scopes = implode("</li><li>", $scopes);

		$form = array();

		foreach ($this->_create_form as $name => $label)
		{
			if ( ! is_array($label))
			{
				$form[] = "<label for='$name'>$label</label>\n\t\t\t<input type='text' name='$name' id='$name' class='span10'>";
			}
			else
			{
				if (isset($label['as']) AND ! isset($label['at']))
				{
					
					switch ($label['as'])
					{
						case 'textarea':
							$field = form_textarea(array('name' => $name, 'id' => $name, 'class'=>'span10'));
							break;
						case 'select':
							if (strpos($label['model'], '/') !== FALSE)
							{
								$model_name = preg_replace('/[a-zA-Z0-9_-]+\/([a-zA-Z0-9_-]+)_model/', '$1', $label['model']);
							}
							else
							{
								$model_name = preg_replace('([a-zA-Z0-9_-]+)_model/', '$1', $label['model']);
							}

							$field = "<?php echo form_dropdown('$name', \${$model_name}, '', \"name='$name' id='$name'\"); ?>";
							break;
					}

					$form[] = "<label for='$name'>{$label['label']}</label>\n\t\t\t".$field;
				}
			}
		}

		$edit = array();

		foreach ($this->_create_form as $name => $label)
		{
			if ( ! is_array($label))
			{
				$edit[] = "<label for='$name'>$label</label>\n\t\t\t<input type='text' name='$name' id='$name' class='span10' value='<?php echo \${$this->_resource_singular}->$name ?>'>";
			}
			else
			{
				if (isset($label['as']) AND ! isset($label['at']))
				{
					
					switch ($label['as'])
					{
						case 'textarea':
							$field = "<textarea name='$name' id='$name' class='span10' cols='90' rows='12' ><?php echo \${$this->_resource_singular}->$name ?></textarea>";
							break;
						case 'select':
							if (strpos($label['model'], '/') !== FALSE)
							{
								$model_name = preg_replace('/[a-zA-Z0-9_-]+\/([a-zA-Z0-9_-]+)_model/', '$1', $label['model']);
							}
							else
							{
								$model_name = preg_replace('([a-zA-Z0-9_-]+)_model/', '$1', $label['model']);
							}

							$field = "<?php echo form_dropdown('$name', \${$model_name}, \${$this->_resource_singular}->$name, \"name='$name' id='$name'\"); ?>";
							break;
					}

					$edit[] = "<label for='$name'>{$label['label']}</label>\n\t\t\t".$field;
				}
			}
		}


		$filters = array();

		foreach ($this->_filter_by as $field => $label)
		{
			if (strpos($field, '(') !== FALSE)
			{				
				$name = preg_replace('/.*\(([a-zA-Z0-9-_]+)\)/', '$1', $field);
			}
			else
			{				
				$name = preg_replace('/.*\./', '', $field);
			}

			if (is_array($label))
			{
					switch ($label['as'])
					{
						case 'textarea':
							$field = form_textarea(array('name' => $name, 'id' => $name, 'class'=>'span10'));
							break;
						case 'select':
							if (strpos($label['model'], '/') !== FALSE)
							{
								$model_name = preg_replace('/[a-zA-Z0-9_-]+\/([a-zA-Z0-9_-]+)_model/', '$1', $label['model']);
							}
							else
							{
								$model_name = preg_replace('([a-zA-Z0-9_-]+)_model/', '$1', $label['model']);
							}

							$field = "<?php echo form_dropdown('$name', \${$model_name}, _get('$name'), \"name='$name' id='$name'\"); ?>";
							break;
					}
				$filters[] = "<label for='$name'>{$label['label']}</label>\n\t\t\t".$field;
			}
			else
			{

				$filters[] = "<label for='$name'>$label</label>\n\t\t\t<div class='input'><input type='text' name='$name' id='$name' class='xlarge' value='<?php echo _get('$name') ?>'></div>";
			}
		}

		$form = implode("\n\t\t</li>\n\t\t<li>\n\t\t\t", $form);
		$edit = implode("\n\t\t</li>\n\t\t<li>\n\t\t\t", $edit);
		$filters = implode("\n\t\t</div>\n\t\t<div class='clearfix'>\n\t\t\t", $filters);

		$index = <<<INDEX
<h2>{$resource}</h2>
<div class="row">
	<div class="span16">
		<ul class="scopes"><li>$scopes</li></ul>
		<?php echo \$table ?>
	</div>
</div>
<style>
.scopes{overflow:hidden;list-style:none}
.scopes li{float:left;padding:5px;list-style-type:none;}
</style>
INDEX;

		$menu = <<<MENU
<div class="well">
  <h5>Actions</h5>
  <ul>
    <li><?php echo anchor('admin/{$this->_resource}/create', 'New {$this->_resource_singular}') ?></li>
  </ul>
</div>
MENU;

		$create = <<<CREATE
<div class="row">
	<div class="span16">
		<?php echo form_open('admin/{$this->_resource}/save'); ?>
		<ul>
			<li>
				$form
			</li>
			<li>
				<input type="submit" class="btn success" value="Save changes">&nbsp;<button type="reset" class="btn">Cancel</button>
			</li>
		</ul>
		<?php echo form_close() ?>
	</div>
</div>
CREATE;

		$filters = <<<FILTERS
<div class="row">
	<div class="span16">

		<?php echo form_open('admin/{$this->_resource}', array('method' => 'get')); ?>
		<fieldset>
        	<legend>Filter By</legend>
        		<div class='clearfix'>
					$filters
				</div>
				<input type="submit" class="btn small success" value="Filter">
				<?php echo anchor('admin/{$this->_resource}', 'Reset', array('class' => 'btn small')); ?>
			</fieldset>
		<?php echo form_close() ?>
	</div>
</div>

FILTERS;

$details = <<<DETAILS
<div class="row">
	<div class="span16">
		<?php echo form_open('admin/{$this->_resource}/save'); ?>
		<ul>
			<li>
				$edit
			</li>
			<li>
				<input type="hidden" name="id" value="<?php echo \${$this->_resource_singular}->id ?>" />
				<input type="submit" class="btn success" value="Save changes">&nbsp;<button type="reset" class="btn">Cancel</button>
			</li>
		</ul>
		<?php echo form_close() ?>
	</div>
</div>

DETAILS;

		write_file($this->_module_path.'/views/index.php', $index);
		write_file($this->_module_path.'/views/form_create.php', $create);
		write_file($this->_module_path.'/views/menu/actions.php', $menu);
		write_file($this->_module_path.'/views/form_filters.php', $filters);
		write_file($this->_module_path.'/views/details.php', $details);
	}


}