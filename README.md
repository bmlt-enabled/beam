beam
===

[![Build Status](https://travis-ci.org/bmlt-enabled/beam.svg?branch=master)](https://travis-ci.org/bmlt-enabled/beam)

Beam is a self-hosted experimental project that would allow service bodies communicate both within and to other service bodies.  

It also intends to serve as a source of truth for trusted servant contact information, the same principle way the BMLT handles meeting information.

It uses the BMLT to retrieve information about the service structure and in the future various other details.

To configure you will need the following.

1) An instance capable of hosting PHP 7.1 or greater (preferrably hosted using Apache or NGINX).
2) A MySQL database.
3) An existing BMLT Server.
4) An SMTP server (or hosted service), which will in currently used for password resets.
5) An SSL certificate.  You will want to ensure security in-transit of all communications with TLS.

Other communications add-ons could be developed in the future (Slack, Discord, Zoom, etc).

Additional other security hardening tactics & recommendations will be added in the future.

#### Installation (supported on shared hosting now)

1. Upload the zip (https://github.com/bmlt-enabled/beam/releases) your instance.

2. You will need to create a file called `.env` and put it in the root.

It will have the following (see the comments in line for a description)

```ini
BMLT_ROOT_SERVER=          # Your root server address, for example: https://example.org/main_server

APP_ENV=production         # This does not need to change
APP_KEY=                   # Visit this site https://generate.plus/en/base64 (ensure the bit length is set to 32) and generate a random base64 value and populate as "base64:value"
APP_DEBUG=false            # Used for additional debugging, leaving as false for production
APP_URL=http://localhost   # This does not need to change

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=              # Set to the host of your database server, if on the same instance put as localhost
DB_PORT=              # The MySQL database port, default is 3306
DB_DATABASE=          # The name of the database
DB_USERNAME=          # The database username
DB_PASSWORD=          # The database password

MAIL_DRIVER=smtp
MAIL_HOST=            # The SMTP mail server hostname
MAIL_PORT=            # The SMTP mail server port
MAIL_USERNAME=        # The SMTP mail username
MAIL_PASSWORD=        # The SMTP mail password
MAIL_ENCRYPTION=true
MAIL_FROM_ADDRESS=    # The SMTP mail from address
MAIL_FROM_NAME=       # The SMTP mail from name
```

3. Make sure the root of you site is pointed to the `public` folder.

4. After the file is in place you can go to:

https://example.org/beam/utility/migrations and the database will be seeded.

5. Now visit https://example.org/beam/ and click Register in the top right corner.  Create your administrator account.

6. Go into the database and find the `users` table.  In the row with that user update the `type` field from `default` to `admin`.

7. Click reload in your web browser, and now your user with be an administrator.

8. Logs are in `storage/logs` (if needed).

**TODO**

Linking two beam instances together.
