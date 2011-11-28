<?php defined('BASEPATH') or exit('No direct script access allowed');

class Settings {

	protected $ci;

	public function __construct()
	{
		$this->CI = get_instance();
		//$this->CI->load->model('settings/settings_model');
	}

	/**
	 * Getter
	 *
	 * Gets the setting value requested
	 *
	 * @param	string	$name
	 */
	public function __get($name)
	{
		//return self::get($name);
	}

	/**
	 * Setter
	 *
	 * Sets the setting value requested
	 *
	 * @param	string	$name
	 * @param	string	$value
	 * @return	bool
	 */
	public function __set($name, $value)
	{
		//return self::set($name, $value);
	}

	/**
	 * Get
	 *
	 * Gets a setting.
	 *
	 * @param	string	$name
	 * @return	bool
	 */
	public static function get($name)
	{
		if (isset(self::$cache[$name]))
		{
			return self::$cache[$name];
		}

		$setting = ci()->settings_m->get_by(array('slug' => $name));

		// Setting doesn't exist, maybe it's a config option
		$value = $setting ? $setting->value : config_item($name);

		// Store it for later
		self::$cache[$name] = $value;

		return $value;
	}

	/**
	 * Set
	 *
	 * Sets a config item
	 *
	 * @param	string	$name
	 * @param	string	$value
	 * @return	bool
	 */
	public static function set($name, $value)
	{
		if (is_string($name))
		{
			if (is_scalar($value))
			{
				$setting = ci()->settings_m->update($name, array('value' => $value));
			}

			self::$cache[$name] = $value;

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Item
	 *
	 * Old way of getting an item.
	 * @deprecated	v1.0	Use either __get or Settings::get() instead
	 * @param	string	$name
	 * @return	bool
	 */
	public function item($name)
	{
		return $this->__get($name);
	}

	/**
	 * Set Item
	 *
	 * Old way of getting an item.
	 * @deprecated	v1.0	Use either __set or Settings::set() instead
	 * @param	string	$name
	 * @param	string	$value
	 * @return	bool
	 */
	public function set_item($name, $value)
	{
		return $this->__set($name, $value);
	}

	/**
	 * All
	 *
	 * Gets all the settings
	 *
	 * @return	array
	 */
	public function get_all()
	{
		if (self::$cache)
		{
			return self::$cache;
		}

		// FIXME: Put this back after 1.2.2 is released
		// $settings = ci()->settings_m->get_all();
		
		$settings = ci()->db
			->select('*, IF(`value` = "", `default`, `value`) as `value`', FALSE)
			->get('settings')
			->result();

		foreach ($settings as $setting)
		{
			self::$cache[$setting->slug] = $setting->value;
		}

		return self::$cache;
	}

	/**
	 * Add Setting
	 *
	 * Adds a new setting to the database
	 *
	 * @param	array	$setting
	 * @return	int
	 */
	public function add($setting)
	{
		if ( ! $this->_check_format($setting))
		{
			return FALSE;
		}

		return ci()->settings_m->insert($setting);
	}

	/**
	 * Delete Setting
	 *
	 * Deletes setting to the database
	 *
	 * @param	string	$name
	 * @return	bool
	 */
	public function delete($name)
	{
		return ci()->settings_m->delete_by(array('slug' => $name));
	}

	/**
	 * Form Control
	 *
	 * Returns the form control for the setting.
	 *
	 * @param	object	$setting
	 * @return	string
	 */
	public function form_control($setting)
	{
		if ($setting->options)
		{
			// @usage func:function_name | func:helper/function_name | func:module/helper/function_name
			// @todo: document the usage of prefix "func:" to get dynamic options
			// @todo: document how construct functions to get here the expected data
			if (substr($setting->options, 0, 5) == 'func:')
			{
				$func = substr($setting->options, 5);

				if (($pos = strrpos($func, '/')) !== FALSE)
				{
					$helper	= substr($func, 0, $pos);
					$func	= substr($func, $pos + 1);

					if ($helper)
					{
						$this->ci->load->helper($helper);
					}
				}

				if (is_callable($func))
				{
					// @todo: add support to use values scalar, bool and null correctly typed as params
					$setting->options = call_user_func($func);
				}
				else
				{
					$setting->options = array('=' . lang('select.none'));
				}
			}

			if (is_string($setting->options))
			{
				$setting->options = explode('|', $setting->options);
			}
		}

		switch ($setting->type)
		{
			default:
			case 'text':
				$form_control = form_input(array(
					'id'	=> $setting->name,
					'name'	=> $setting->name,
					'value'	=> $setting->value
				));
				break;

			case 'textarea':
				$form_control = form_textarea(array(
					'id'	=> $setting->name,
					'name'	=> $setting->name,
					'value'	=> $setting->value,
					'class'	=> ''
				));
				break;

			case 'password':
				$form_control = form_password(array(
					'id'	=> $setting->name,
					'name'	=> $setting->name,
					'value'	=> $setting->value,
					'autocomplete' => 'off'
				));
				break;

			case 'select':
				$form_control = form_dropdown($setting->name, $this->_format_options($setting->options), $setting->value, '');
				break;

			case 'select-multiple':
				$options = $this->_format_options($setting->options);
				$size = sizeof($options) > 10 ? ' size="10"' : '';
				$form_control = form_multiselect($setting->name . '[]', $options, explode(',', $setting->value), '' . $size);
				break;

			case 'checkbox':

				$form_control = '';
				$stored_values = is_string($setting->value) ? explode(',', $setting->value) : $setting->value;

				foreach ($this->_format_options($setting->options) as $value => $label)
				{
					if (is_array($stored_values))
					{
						$checked = in_array($value, $stored_values);
					}
					else
					{
						$checked = FALSE;
					}
					
					$form_control .= '<label>';
					$form_control .= '' . form_checkbox(array(
						'id'		=> $setting->name . '_' . $value,
						'name'		=> $setting->name . '[]',
						'checked'	=> $checked,
						'value'		=> $value
					));
					$form_control .= ' ' . $label . '</label>';
				}
				break;

			case 'radio':

				$form_control = '';
				foreach ($this->_format_options($setting->options) as $value => $label)
				{
					$form_control .= form_radio(array(
						'id'		=> $setting->name,
						'name'		=> $setting->name,
						'checked'	=> $setting->value == $value,
						'value'		=> $value
					)) . ' ' . $label . ' ';
				}
				break;
		}

		return $form_control;
	}

	/**
	 * Format Options
	 *
	 * Formats the options for a setting into an associative array.
	 *
	 * @param	array	$options
	 * @return	array
	 */
	private function _format_options($options = array())
	{
		$select_array = array();

		foreach ($options as $option)
		{
			if (strpos($option, '=' !== FALSE))
			{
				list($value, $name) = explode('=', $option);
				$select_array[$value] = $name;
			}
			else
			{
				$select_array[] = $option;
			}
		}

		return $select_array;
	}

	/**
	 * Check Format
	 *
	 * This assures that the setting is in the correct format.
	 * Works with arrays or objects (it is PHP 5.3 safe)
	 *
	 * @param	string	$setting
	 * @return	bool	If the setting is the correct format
	 */
	private function _check_format($setting)
	{
		if ( ! isset($setting))
		{
			return FALSE;
		}

		foreach ($setting as $key)
		{
			if ( ! array_key_exists($key, $this->columns))
			{
				return FALSE;
			}
		}

		return TRUE;
	}

}

/* End of file Settings.php */