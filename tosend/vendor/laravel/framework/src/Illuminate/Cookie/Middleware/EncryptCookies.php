<?php namespace Illuminate\Cookie\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Cookie;
use Illuminate\Contracts\Routing\Middleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\Encrypter as EncrypterContract;

class EncryptCookies implements Middleware {

	/**
	 * The encrypter instance.
	 *
	 * @var \Illuminate\Contracts\Encryption\Encrypter
	 */
	protected $encrypter;

	/**
	 * Create a new CookieGuard instance.
	 *
	 * @param  \Illuminate\Contracts\Encryption\Encrypter  $encrypter
	 * @return void
	 */
	public function __construct(EncrypterContract $encrypter)
	{
		$this->encrypter = $encrypter;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		return $this->encrypt($next($this->decrypt($request)));
	}

	/**
	 * Decrypt the cookies on the request.
	 *
	 * @param  \Symfony\Component\HttpFoundation\Request  $request
	 * @return \Symfony\Component\HttpFoundation\Request
	 */
	protected function decrypt(Request $request)
	{
		foreach ($request->cookies as $key => $c)
		{
			try
			{
				$request->cookies->set($key, $this->decryptCookie($c));
			}
			catch (DecryptException $e)
			{
				// START MOD
				// Rename the cookie with a prefix of "unsigned::" to make it
				// clear this cookie wasn't signed
				$request->cookies->set("unsigned::$key", $c);
				// END MOD
				$request->cookies->set($key, null);
			}
		}
		return $request;
	}

	/**
	 * Decrypt the given cookie and return the value.
	 *
	 * @param  string|array  $cookie
	 * @return string|array
	 */
	protected function decryptCookie($cookie)
	{
		return is_array($cookie)
						? $this->decryptArray($cookie)
						: $this->encrypter->decrypt($cookie);
	}

	/**
	 * Decrypt an array based cookie.
	 *
	 * @param  array  $cookie
	 * @return array
	 */
	protected function decryptArray(array $cookie)
	{
		$decrypted = array();

		foreach ($cookie as $key => $value)
		{
			$decrypted[$key] = $this->encrypter->decrypt($value);
		}

		return $decrypted;
	}

	/**
	 * Encrypt the cookies on an outgoing response.
	 *
	 * @param  \Symfony\Component\HttpFoundation\Response  $response
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	protected function encrypt(Response $response)
	{
		foreach ($response->headers->getCookies() as $key => $cookie)
		{
			// START MOD
			$name = $cookie->getName();
			if (starts_with($name, 'unsigned::')) {
				// Remove the cookie with the "unsigned::" prefix
				$response->headers->removeCookie($name, $cookie->getPath(), $cookie->getDomain());
				// Set the unencrypted cookie without the prefix
				$response->headers->setCookie($this->rename(
					$cookie, substr($name, 10)
				));
			} else {
				// END MOD
				$response->headers->setCookie($this->duplicate(
					$cookie, $this->encrypter->encrypt($cookie->getValue())
				));
				// START MOD
			}
			// END MOD
		}
		return $response;
	}

	/**
	 * Duplicate a cookie with a new value.
	 *
	 * @param  \Symfony\Component\HttpFoundation\Cookie  $c
	 * @param  mixed  $value
	 * @return \Symfony\Component\HttpFoundation\Cookie
	 */
	protected function duplicate(Cookie $c, $value)
	{
		return new Cookie(
			$c->getName(), $value, $c->getExpiresTime(), $c->getPath(),
			$c->getDomain(), $c->isSecure(), $c->isHttpOnly()
		);
	}


	/**
	 * Duplicate a cookie with a new name.
	 *
	 * @param  \Symfony\Component\HttpFoundation\Cookie  $cookie
	 * @param  mixed  $name
	 * @return \Symfony\Component\HttpFoundation\Cookie
	 */
	private function rename(Cookie $c, $name)
	{
		return new Cookie(
			$name, $c->getValue(), $c->getExpiresTime(), $c->getPath(),
			$c->getDomain(), $c->isSecure(), $c->isHttpOnly()
		);
	}

}
