<?php

class Admin extends Admin_Controller {
	
	function index()
	{
		$this->template->set_content('main', 'generators_list')
					   ->render();
	}

	function folders()
	{		
		$this->template->set_content('main', 'folders_list')
					   ->render();
	}

	function create_folders()
	{
		$created = array();

		if (is_dir(APPPATH.'modules'))
		{
			$original = _post('module_name');
			$module = strtolower($original);
			$module_path = APPPATH.'modules/'.$module;

			$this->_make_dir($module_path);

			if (_post('mvc'))
			{
				$created[] = $this->_make_dir($module_path.'/controllers');
				$created[] = $this->_make_dir($module_path.'/models');
				$created[] = $this->_make_dir($module_path.'/views');
				$created[] = $this->_make_dir($module_path.'/views/sidebar');

				$base = <<<BASE
<h2>Title</h2>
<div class="row">
	<div class="span16">

	</div>
</div>
BASE;
				write_file($module_path.'/views/create.php', $base);
				write_file($module_path.'/views/details.php', $base);
				write_file($module_path.'/views/edit.php', $base);
				write_file($module_path.'/views/index.php', $base);

				$sidebar = <<<SIDEBAR

<div class="span4">
	<div class="well">
	  <h5>Title</h5>
	  <ul>
	    <li><?php echo anchor('#', 'Item') ?></li>
	  </ul>
	</div>
</div>

SIDEBAR;
				write_file($module_path.'/views/sidebar/index.php', $sidebar);

				if (_post('admin-controller'))
				{
					$admin = <<<ADMIN
<?php

class Admin extends Admin_Controller {
	
	function index()
	{
		
		\$this->template->set_content('main', 'index')
					   ->set_content('menu', 'sidebar/index')
					   ->render();
	}
}
ADMIN;
					if ( ! file_exists($module_path.'/controllers/admin.php'))
					{						
						write_file($module_path.'/controllers/admin.php', $admin);
						$created[] = $module_path.'/controllers/admin.php'.br();
					}
				}

				if (_post('front-controller'))
				{
					$front = <<<FRONT
<?php

class $original extends Front_Controller {
	
	function index()
	{
		
	}
}
FRONT;
					if ( ! file_exists($module_path.'/controllers/'.$module.'.php'))
					{
						write_file($module_path.'/controllers/'.$module.'.php', $front);
						$created[] = $module_path.'/controllers/'.$module.'.php'.br();
					}
				}

				if (_post('model-file'))
				{
					$model = <<<MODEL
<?php

class {$original}_Model extends MY_Model {
	
}
MODEL;
					if ( ! file_exists($module_path.'/models/'.$module.'_model.php'))
					{
						write_file($module_path.'/models/'.$module.'_model.php', $model);
						$created[] = $module_path.'/models/'.$module.'_model.php'.br();
					}
				}
			}

			if (_post('config'))
			{
				$created[] = $this->_make_dir($module_path.'/config');
			}

			if (_post('lang'))
			{
				$created[] = $this->_make_dir($module_path.'/language');
			}

			if (_post('libraries'))
			{
				$created[] = $this->_make_dir($module_path.'/libraries');
			}

			if (_post('helpers'))
			{
				$created[] = $this->_make_dir($module_path.'/helpers');
			}
		}
		
		Message::success(implode('', $created), 'admin/generators/folders');
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

	function create_forms()
	{
		
		$this->template->set_content('main', 'form_generator')
					   ->js('form_generator')
					   ->set_content('menu', 'menu/form_generator')
					   ->render();
		
	}

	function generate_form()
	{
		$content = html_entity_decode(_post('content'));

		$content = preg_replace('/<([^>]+)>/i', "\n<$1>\n", $content);

		$content = str_ireplace('</ul>', '<li>
			<input type="submit" class="btn success" value="Save">&nbsp;<button type="reset" class="btn">Cancel</button>
		</li></ul>', $content);

		$content = '<?php echo form_open(""); ?>'.$content.'<?php echo form_close(); ?>';

		write_file(APPPATH.'modules/otro/views/new_form.php', $content);
	}
}