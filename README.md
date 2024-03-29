# anx-api-proxy (AppNexus API Proxy)
> A utility API endpoint to communicate with AppNexus

This service simplify AppNexus communication with API Key(s).  It also simplify downloading of generic reports.

# Features
- [x] Proxy (GET, POST, PUT, DELETE) with cached authentication
- [x] Generic Advertiser reports download
- [ ] Generic Publisher reports download
- [ ] Handle X-Rate-Limit header

## Installation
1. `git clone https://github.com/niiknow/anx-api-proxy`
2. `cd anx-api-proxy`
3. `composer install`
4. `npm install`
5. set your `.env` by copying from `.env.example`
6. `php artisan key:generate`
7. Run/Serve the Site
    - laravel valet: `valet link anx-api-proxy`
    - homestead: `homestead up`
8. after `valet link anx-api-proxy`, visit [anx-api-proxy.test/](http://anx-api-proxy.test) or npm run watch
9. visit [anx-api-proxy.test/api/documentation](http://anx-api-proxy.test/api/documentation) for api docs

## Shared Hosting Deployment
1. Package the project, this will create `dist.tar` file
```
composer app:package
```
2. Create the necessary database and user on your server.  Take note of the database credentials; we will use it on `step 7` below.
![](https://raw.githubusercontent.com/niiknow/anx-api-proxy/master/storage/docs/step2.png?raw=true)
3. Create sub-domain and its folder, example: `anx-api-proxy.niiknow.org` with folder as `/home/{user}/anx-api-proxy.niiknow.org`
![](https://raw.githubusercontent.com/niiknow/anx-api-proxy/master/storage/docs/step3.png?raw=true)
4. Update your hosting folder as `/home/{user}/anx-api-proxy.niiknow.org/public`
![](https://raw.githubusercontent.com/niiknow/anx-api-proxy/master/storage/docs/step4-1.png?raw=true)
![](https://raw.githubusercontent.com/niiknow/anx-api-proxy/master/storage/docs/step4-2.png?raw=true)
5. Upload the resulting file in `dist.tar` to your sub-domain folder `/home/user/anx-api-proxy.niiknow.org/` (note **not** your public folder) and extract it to the current director.
![](https://raw.githubusercontent.com/niiknow/anx-api-proxy/master/storage/docs/step5.png?raw=true)
6. Visit `http://anx-api-proxy.niiknow.org/init.php` to initialize the project.  This will update required permissions for `storage/framework/`, `storage/logs/`, and `bootstrap/cache/` and create the `.env` file from `.env.example` file.  If it doesn't automatically redirect you to `/install`, then visit `/install` and complete the setup to finalize your `.env` file with the necessary database and other configuration.  Take note of the `API_KEYS` that was generated or provide your own key to be use with `X-API-Key` header.  Take note of `REPORT_KEY` for report authentication.
![](https://raw.githubusercontent.com/niiknow/anx-api-proxy/master/storage/docs/step6-1.png?raw=true)
![](https://raw.githubusercontent.com/niiknow/anx-api-proxy/master/storage/docs/step6-2.png?raw=true)
7. Follow the installation wizard.  Fill in the necessary `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` that you've taken note earlier.  Make sure you fill in everything such as (`APP_URL`, `APP_DOMAIN`, `APP_DEBUG`, etc...) and not just the database values.  Then click `save .env`, you want to make sure you save your changes.  Then click `save and install`, this will perform database initial migration for you.  Then click the `click here to exit` button to complete the installation.
![](https://raw.githubusercontent.com/niiknow/anx-api-proxy/master/storage/docs/step7-1.png?raw=true)
![](https://raw.githubusercontent.com/niiknow/anx-api-proxy/master/storage/docs/step7-2.png?raw=true)
8. Congratulation, you're all set!  You can always update your `.env` file now if you need to make any additional changes.

**TO UPDATE/UPGRADE**
1. run `composer app:package` again
2. upload and extract like you've done in installation above
3. public/init.php - execute this by visiting example.app/init.php in the browser
4. then visit your site `/update` instead of `/install`

**Configuration/env Note**
- `API_KEYS`=set this to secure your api with `X-API-Key` header
- `REPORT_KEY`=set this to authenticate with report download

### Troubleshooting
If you get redirect to `/install` and the page is blank.  Check your cpanel error log for details.  Make sure you pick the correct php version.  Some older server default to `php7+` instead of `php8+`.

If you have an issue and need to restart installation, simply delete the file `storage/installed` and visit `/install` again.

# Note
At the moment, we really have no reason for having a database since this is only use as a Proxy.

# MIT
