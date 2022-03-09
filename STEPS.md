# Steps

## System
Running Valet+ (weprovide)
- Composer version 2.2.7 2022-02-25 11:12:27
- PHP 8.0.3 (henkrehorst brew tap) ALTERATION due to technical limitation / deprecated workaround
- nginx version: nginx/1.21.6
- mysql-client: mysql Ver 8.0.28 for macos10.15 on x86_64 (Homebrew)
- mysql 8.0.19 Port 3310 (DBngin)
- node v14.15.4 (node_modules in public/assets)

ALTERATION:
Controller args / annotations using e.g. #[CurrentUser] and do not work with php7.4, rolling over to php8.0.
Reason being Sensio extraframeworkbundle is being phased out. 

## Install
composer install
bin/console doctrine:database:create
bin/console make:migration (should already have everything)
bin/console doctrine:migrations:migrate

set an URL for this site via valet+. 

Node scripts can be run via public/assets. 
"npm run" will give a list of what is available. 

## Site
- {url}/ can be visited. Blocked out, should redirect you to /login
- No account yet, take it to registration. 
- Modified behaviour, so you should be logged in after registration
- Sends you to the homepage. This is where the form for conversion is located. 
- CurrConv.com does not always work. 
- Kowabunga did not exists (anymore?)
- implemented ExchangeRatesApi. This one also has a hiccup. The free plan does not let you set a base currency. 
- On ExchangeRatesApi, backend will calculate back from e.g. USD -> INR while base is EUR. 
- example: i want base INR. Target USD. Rates are given by the api in EUR. Divide the $target / $base and we have an approximation. 
- For reduction of API calls, Symfony first checks if there is already a record for today's rate between two given pairs. 
- If no record was found, API gets called and if there is a successful call, this one gets added into the database as a record. 

## Add a new provider
Go to src/CurrencyConversion/Provider/ and copy one of the existing ones, modify as needed. Especially the normalization is important. 

Go to app/services.yaml and register the new Provider as a service, again, copy-paste and modify. 

Done. 
