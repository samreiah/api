Click here to reset your password: 
	<a href="{{ $link = $url.'/forgot/password/reset/'.$token.'/'.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
