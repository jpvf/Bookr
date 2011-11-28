<?php

function today($hour = FALSE)
{
	return ($hour === FALSE ? date('Y-m-d'): date('Y-m-d H:i:s') ); 
}