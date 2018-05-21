<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use View;

use App\Http\Controllers\Controller;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class PasswordResetController extends ApiController
{
    public function postEmail(Request $request) {
		
       $this->validate($request, ['email' => 'required|email']);
	   
		view()->share('url', $request->input('linkurl'));

		$response = Password::sendResetLink($request->only('email'), function (Message $message) {
			$message->subject($this->getEmailSubject());
			$message->from('WebMaster@technocarrot.com');
		});

		switch ($response) {
			case Password::RESET_LINK_SENT:
				return $this->respondWithSuccess('RESET_LINK_SENT','Reset link sent in email!');

			case Password::INVALID_USER:
               return $this->respondInternalError('INVALID_USER','Invalid email!');
		}
   }
   
   /**
    * Get the e-mail subject line to be used for the reset link email.
    *
    * @return string
    */
   protected function getEmailSubject()
   {
       return isset($this->subject) ? $this->subject : 'Your Password Reset Link';
   }
   
    /**
    * Reset the given user's password.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function postReset(Request $request)
   {
       $this->validate($request, [
           'token' 	=> 'required',
           'email' 	=> 'required|email',
           'password' => 'required',
       ]);

       $credentials = $request->only(
           'email', 'password', 'password_confirmation', 'token'
       );

       $response = Password::reset($credentials, function ($user, $password) {
           $this->resetPassword($user, $password);
       });

       switch ($response) {
           case Password::PASSWORD_RESET:
				return $this->respondWithSuccess('PASSWORD_RESET','Password successfully changed, now you can login!');

           default:
				return $this->respondInternalError('INTERNAL ERROR', trans($response), $request->only('email'));
       }
   }

   /**
    * Reset the given user's password.
    *
    * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
    * @param  string  $password
    * @return void
    */
   protected function resetPassword($user, $password)
   {
       $user->password = bcrypt($password);
       $user->save();
   }
}
