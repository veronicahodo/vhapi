# vhapi

The basic template needed to start building an API in PHP

## Installation

Just clone the repository and move the `src` folder into your project folder. Modify `config.php` to your desired options.

## Usage

### Basic

Simply `require_once('api.php');` and extend the `Api` class. The object and filename must be the same (with .php appened of course). In the `__construct` function, we must pass `Vcrud`. Also in the constructor we set up `$this->unit` and `$this->commands` to tell **Api** what commands are available and what unit to report in errors. An example setup is like so:

```
require_once('api.php');

class User extends Api {
    function __construct(Vcrud $crud) {
        parent::__construct($vcrud);
        $this->unit = 'user';
        $this->commands = [
            'add' => [
                ['token',true],
                ['username',true]
            ]
        ];
    }
}
```

Structure for `$this->commands` is associated array of additional arrays, all containing a **fieldname** and if it is required for this command or not.

`index.php` is called by the client, with `unit` being passed as the filename (and object name) which will then call `do` + first uppercase of the command name (i.e. `doAdd`). This function must exist or you will get errors.

Some things accessable to the extended class:
`$this->input` is an array of all fields passed that are within the accepted command parameters.
`$this->userId` is the current userId of the user logged in. This is populated if `token` is passed (and of course, a valid token).

### Tokens

> [!WARNING]
> This project does not include any form of user management or authentication processing. That must all be developed by you. This only looks for a pre-generated token that has already been inserted to the database.

This does support a basic form of authentication using a token scheme that requires the database to have a `tokens` table, with the following fields:

```
    userId bigint
    token varchar
    expiration varchar
```

`expiration` is just the `date('YmdHis')` of the expiration of the token.

## Future

Eventually I will get this to add some logging functionality.
