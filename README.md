# "Named Params"

Not really named params in calls, but allows for fluent, readable calls on both functions and methods.

## Examples

In the following example, we create a named param object for `password_hash`. It's not a function with particularly confusing arguments, but some use can still be wrung from minimizing the required arguments.
```php
// Create an object representing the function to be called
$password_hash = new Mpw\NamedParamFunctionCall('password_hash');

// fill in the arguments
$password_hash
    ->withArg('password', 'CorrectHorseBatteryStaple')
    ->withArg('algo', PASSWORD_DEFAULT)
    ->withArg('options', [])
    ->__invoke();

// Each `withArg` call returns a *new instance*
// meaning that fun stuff like this can be done:

$password_hash_default = $password_hash->withArg('algo', PASSWORD_DEFAULT);

$password1 = $password_hash_default('my password');
$password2 = $password_hash_default('your password');
```

Using these objects as dependencies in a class can be a much more lightweight approach than building an entire class that requires testing!

```php

class UserRegistrar
{
    /**
     * @var callable
     */
    private $pwd_hasher;

    /**
     * @param callable $pwd_hasher
     */
    public function __construct(callable $pwd_hasher)
    {
        $this->pwd_hasher = $pwd_hasher;
    }

    public function registerUser($username, $password)
    {
        $hashed_password = $this->pwd_hasher($password);
    }
}
```

This is quite handy, as the `$pwd_hasher` property can be stubbed to create deterministic test results
