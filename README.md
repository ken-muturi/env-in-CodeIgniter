# Adding `.env` file support in CodeIgniter 

This is a showcase how you can implement .env file structure in your CodeIgniter application, and make configurations easy for all the development, production, testing stages. You don't need to take care of `application/config/database.php` file each time you do `git pull` or put it in `.gitignore`. 

This is an update folk from https://github.com/technoknol/env-in-CodeIgniter.

## Steps
##### 1. First add a package `vlucas/phpdotenv` via composer 
> Run `composer require vlucas/phpdotenv` command in your project root directory. (If you don't have composer.json, don't worry, composer will take care of it.
##### 2. Copy a file `Dotenv.php` to your `application/libraries` directory.
> This will load your env file in environment.

##### 3. Copy `env_helper.php` to your `application/helpers` directory.
> This will add `env` helper method to get any variable stored in `.env` file.

##### 4. Copy `MY_Loader.php` to your `application/core` directory.
> This will override Core CI Loader allowing the dotenv file to be loaded as the first lib. 
> Seeing the exact same thing on CI3. It looks like the database helper will be loaded before the .env files are processed which causes any env() calls in the database.php to fail. solving issue raised here https://github.com/technoknol/env-in-CodeIgniter/issues/8

```
		// Load libraries
		// In system\core\Loader.php, they prioritize loading of the database before any other libraries:
		if (isset($autoload['libraries']) && count($autoload['libraries']) > 0)
		{
			// Load the database driver.
			if (in_array('database', $autoload['libraries']))
			{
				$this->database();
				$autoload['libraries'] = array_diff($autoload['libraries'], array('database'));
			}

			// Load all other libraries
			$this->library($autoload['libraries']);
		}

    // If you don't mind touching the Code Igniter code, you can workaround this issue by forcing env to be loaded before database like:
		// Load libraries
		if (isset($autoload['libraries']) && count($autoload['libraries']) > 0)
		{
			// Load the environment driver before the database code
			if (in_array('env', $autoload['libraries']))
			{
				$this->library('env');
				$autoload['libraries'] = array_diff($autoload['libraries'], array('env'));

			// Load the database driver.
			if (in_array('database', $autoload['libraries']))
			{
				$this->database();
				$autoload['libraries'] = array_diff($autoload['libraries'], array('database'));
			}

			// Load all other libraries
			$this->library($autoload['libraries']);
		}
    
```
##### 5. Autoload library 
> Add library to `$autoload['libraries']` like this 
```
$autoload['libraries'] = array('dotenv');
```
##### 6. Autoload helper (application/config/autoload.php)
> Add helper to `$autoload['helper']` like this 
```
$autoload['helper'] = array('env');
```

##### 7. Autoload helper (application/config/config.php)
> Make `composer_autoload` to `vendor/autoload.php` file path like this in `config.php` file 
```
$config['composer_autoload'] = FCPATH. 'vendor'. DIRECTORY_SEPARATOR . 'autoload.php';
```

##### 8. Create an `.env` file
Create an `.env` file in your project root folder. 

##### 9. Access an env variable
> In your php code you can access any .env variable like below 
```
Env::get('MY_VARIABLE');
```

Voila ! Now you can have multiple variables based on Environment, and don't need to manually change file and then put it in `.gitignore`.
