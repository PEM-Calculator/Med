<?php

namespace Med\Models;

use \Med\Libraries\Errors;

class UserLogin extends \Med\Models\BaseModel
{
	public
		$id_user,
		$time_login,
		$time_live,
		$time_logout,
		$hash,
		$ip,
		$client_info;
}