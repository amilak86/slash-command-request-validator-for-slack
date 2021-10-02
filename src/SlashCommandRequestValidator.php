<?php
namespace Ak86;

class SlashCommandRequestValidator
{
	/** @var int $reqAllowedWithin sets the number of seconds till a request is considered valid */
	public static $reqAllowedWithin = 300;		// in seconds

	/**
	 * Validates a request to see if it meets required criterias
	 * 
	 * @return void
	 */
	public static function validate()
	{
		// get request headers
		$reqheaders = self::getRequestHeaders();

		// get the raw request body
		$payload = self::getRequestBody();

		// The request method must be POST
		if($_SERVER['REQUEST_METHOD'] != 'POST')
		{
			throw new Exception('Invalid request method!');
		}

		// validate request timestamp
		// if the request timestamp is more than five minutes from local time
		// it could be a replay attack, so ignore it
		if(abs($reqheaders['X-Slack-Request-Timestamp'] - time()) > self::$reqAllowedWithin);
		{
			throw new Exception('Request timestamp exceeds the valid timeframe allowed!');
		}

		// don't allow empty payload/body
		if(!$payload)
		{
			throw new Exception('The request payload can\'t be empty!');
		}

		// validate slack signature
		// construct the basestring for signature verification
		$sig_basestring = 'v0:' . $reqheaders['X-Slack-Request-Timestamp'] . ':' . $payload;

		// hash the above basestring using the signing secret as a key
		$my_signature = 'v0=' . hash_hmac('sha256', $sig_basestring, getenv('SLACK_SIGNING_SECRET'));

		// compare $my_signature with X-Slack-Signature
		if(!hash_equals($reqheaders['X-Slack-Signature'], $my_signature))
		{
			throw new Exception('Slack signature verification failed!');
		}
	}

	/**
	 * Retrieve the raw request body
	 *
	 * @return string
	 */
	private static function getRequestBody()
	{
		return file_get_contents("php://input");
	}

	/**
	 * Retrieve the request headers
	 * 
	 * @return array
	 */
	private static function getRequestHeaders()
	{
		return getallheaders();
	}

}
