# Auth0 Middleware
This Auth0 Middleware framework provides a simple, standalone, PHP framework to handle authentication via Auth0.
Included is a php integration script that should be copied into

### Requirements
 - This framework needs to reside on the same subdomain as 
 - HTTPS Certificates
 - Auth0 Account

### More Stuff
Add more stuff


### How to integrate
Simples. 
1. Set up the authentication framework via Apache or Nginx
2. Copy the integration into your application
3. Add a Snippet to your code to confirm the integration

#### 1. Set up Auth framework
The authentication routes are set up on the the `/auth` routes. This should be taken into account when setting up your webserver.

##### 1a. Apache Setup
tbd

##### 1b. Nginx Setup
tbd

##### 1c. Heroku
Currently, we host this via heroku. In order to host on heroku a `Procfile` was created, specifying the use of nginx, and a `nginx_app.config` config file. 
Heroku is tied to snoop the `master` branch, so anything pushed to `master` will be deployed on Heroku _(circa Nov 19)_.

#### 2. Copy the integration
The php integration for this can be found at `integrations/php-app.php`, in the integrations folder. 
`require()` this script within your application.
This script will check the auth cookie for a successful session. If it's not set or invalid it will redirect to the login endpoint.

#### 3. Add the code snippet
In order to use this you can just do the following logic. Check if AUTHED_EMAIL isn't NULL. Then check within your application for permissions.
```
if (AUTHED_EMAIL !== NULL) {
    // Do your stuff
}
```
