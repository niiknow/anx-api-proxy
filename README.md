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

**Production Deployment**
1. Package the project
```
composer app:package
```
2. Upload the resulting file in `storage/build/dist.tar.gz` to your server and unpack/extract.
3. Create the necessary database on your server and take note of the database credentials.
4. Visit `your-api.example.com/init.php` to initialize the project.  This will update required permissions for `storage/framework/`, `storage/logs/`, and `bootstrap/cache/` and create the `.env` file from `.env.example` file.  If it doesn't automatically redirect you to `/install`, then visit `/install` and complete the setup to finalize your `.env` file with the necessary database and other configuration.  Take note of the `API_KEYS` that was generated or provide your own key to be use with `X-API-Key` header.  Take note of `REPORT_KEY` for report authentication.
5. Congratulation, you're all set!

**Configuration/env Note**
- `API_KEYS`=set this to secure your api with `X-API-Key` header
- `REPORT_KEY`=set this to authenticate with report download

# Note
At the moment, we really have no reason for having a database since this is only use as a Proxy.

# MIT
