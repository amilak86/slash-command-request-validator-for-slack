# Slash Command Request Validator for Slack Apps

A simple PHP package for validating the requests sent through a slack app slash commands. The package aims to address the HTTP endpoint requirements outlined as follows:

- Validates the HTTP request method to make sure it is a POST
- Validates **X-Slack-Request-Timestamp** header to mitigate replay attacks
- Validates the HTTP payload to make sure it is not empty
- Verifies the slack request signature

Please refer to below Slack documentation for the complete information on its endpoint requirements:

- [Enabling interactivity with Slash Commands](https://api.slack.com/interactivity/slash-commands)
- [Verifying requests from Slack](https://api.slack.com/authentication/verifying-requests-from-slack)


## Requirements
- PHP 5.6 or higher

## Installation
You can install the package with Composer, by running the below command in your project root folder:
```
composer require ak86/slash-command-request-validator-for-slack
``` 

## Basic Usage
In your app where slack slash command is configured to send its requests:
```
// require composer autoloader
require_once 'vendor/autoload.php';

use Ak86\SlashCommandRequestValidator;

try {
	// simply call validate() static method to validate the incoming request
	SlashCommandRequestValidator::validate()
}
catch (Exception $e){
	echo $e->getMessage();
	exit;
}

```
Optionally, you can set the number of seconds since the originating timestamp which is used to determine for how long the request can be considered as safe:
```
// set the number of seconds
SlashCommandRequestValidator::reqAllowedWithin = 500;

// call validator
SlashCommandRequestValidator::validate()

```

## License

[MIT](./LICENSE)

## Author

[Amila Kalansooriya](https://www.linkedin.com/in/amilakalansooriya/)