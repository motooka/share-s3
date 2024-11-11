# ShareS3
Share your files on Amazon S3 (or compatible one) to clients

## Dependencies
- PHP 8.1+
- AWS SDK for PHP (installed via Composer)
    - IAM User/Role is also required
- some CakePHP 5.x dependencies
    - `mod_rewrite` if you are using Apache
    - `mbstring` PHP extension
    - `intl` PHP extension
    - `xml` PHP extension
    - for more details, refer [Installation page of CakePHP 5.x CookBook](https://book.cakephp.org/5/en/installation.html)
- (no databases)

## Local Development
- You can use Docker (Docker Compose) for local environment
    - to setup
        - `docker compose build`
        - `docker compose up -d`
        - `docker compose exec web bash /composer_install.sh`
        - edit `webapp/config/app_local.php` to configure
    - to start : `docker compose start`
        - this allows you to access your local environment via http://localhost:8080
    - to stop : `docker compose stop`
    - to destroy : `docker compose down`
- You can also build your local environment without Docker, if your machine satisfy the above "Dependencies"

## IAM User vs IAM Role
- if you are going to use this app on EC2 or ECS => you will need to use IAM Role
- others => you will need to use IAM User

## Permissions required to IAM User / Role
- `ListBucket` permission to the target bucket
- `GetObject` permission to any object on the target bucket

## Limits
- If a "directory" contains 1,000 or more objects, they won't be listed. You WILL NOT be able to configure this threshold even on the future versions of this app : because you SHOULD use CLIs or programs to deal with such use-cases.
- If your "directory" name ends with `..`, accesses will be blocked by security reasons. This also will not be changed in the future.
- If your object key contains `"`, some features including downloads won't work.

## License
MIT License. see [LICENSE](./LICENSE) for more details

## Other Notes
- `DebugKit` (official CakePHP plugin) doesn't work if no datasource exists, at this point. So, this app disables the plugin.
