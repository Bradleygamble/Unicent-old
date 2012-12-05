<?php

class Template
{

	private $buffer;
	private $parsed;
	private $input;

	public function __construct()
	{
		
	}

	public function render($view, $data = array(), $return = FALSE)
	{
		$this->input = $data;

		if(file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $view . '.php'))
		{
			if($return == TRUE)
			{
				return file_get_contents(ROOT . DS . 'application' . DS . 'views' . DS . $view . '.php');
			}
			else
			{
				$this->buffer = file_get_contents(ROOT . DS . 'application' . DS . 'views' . DS . $view . '.php');

				$this->buffer = $this->_parse_vars($this->buffer, $data);

				echo $this->_process($this->buffer);
			}
		}
	}

	public function free_render($view, $return = FALSE)
	{
		if(file_exists($view))
		{
			if($return == FALSE)
			{
				include $view;
			}
			else
			{
				return file_get_contents($view);
			}
		}
	}

	private function _show_var($var)
	{
        if (isset($this->input[$var])) 
        {
        	if(is_array($this->input[$var]))
        	{
        		foreach($this->input[$var] as $key => $value)
        		{
        			echo $value;
        		}
        	}
        	else
        	{
            	echo $this->input[$var];
        	}
        } else {
            echo '{' . $var . '}';
        }
    }

    private function _show_array($array, $divider)
    {
    	$start = ' ';

    	if($divider == 'list')
    	{
    		$start = '<li>';
    	}
    	else if($divider == 'block')
    	{
    		$start = '<blockquote>';
    	}

    	$find = array(
    		'comma', 'break', 'list', 'block'
    		);

    	$search = array(
    		',', '<br />', '</li>', '</blockquote>'
    		);

    	$divider = str_replace($find, $search, $divider);

    	if (isset($this->input[$array])) 
    	{
        	if(is_array($this->input[$array]))
        	{
        		$loops = count($this->input[$array]);
        		$loop = 0;

        		foreach($this->input[$array] as $key => $value)
        		{
        			if($loops != $loop)
        			{
        				echo $start;
        			}

        			$loop++;

        			echo $value;

        			if($loops != $loop)
        			{
        				echo $divider;
        			}
        		}
        	}
        	else
        	{
            	echo $this->input[$array];
        	}
        } else {
            echo '{' . $array . '}';
        }
    }

	private function _parse_vars($input, $array)
	{	
		
		$this->parsed = str_replace('<', '<?php echo \'<\'; ?>', $input);
		$this->parsed = preg_replace('~\{(\w+), \"(\w+)\"\}~', '<?php $this->_show_array(\'$1\', \'$2\'); ?>', $this->parsed);
		$this->parsed = preg_replace('~\{(\w+)\}~', '<?php $this->_show_var(\'$1\'); ?>', $this->parsed);
		$this->parsed = preg_replace('~\{FOR:(\w+)\}~', '<?php foreach ($this->data[\'$1\'] as $ELEMENT): $this->wrap($ELEMENT); ?>', $this->parsed);
        $this->parsed = preg_replace('~\{/FOR:(\w+)\}~', '<?php $this->unwrap(); endforeach; ?>', $this->parsed);

		$this->parsed = '?>' . $this->parsed;

		return $this->parsed;
	}

	private function _process()
	{
		ob_start ();
        eval (func_get_arg(0));

        return ob_get_clean();
	}

}