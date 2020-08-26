# laravel-ses-tracker
A Laravel 6.0+ Package that allows you to get simple sending statistics for emails you send through SES, including deliveries, opens, bounces, complaints and link tracking.

> This is a revamped version of [oliveready7/laravel-ses](https://github.com/oliveready7/laravel-ses) package, updated to support Laravel v6.0+ and some bug fixes.
Primarily I revamped this package so it's suitable to be used in my company project with Laravel v6.0+.

Here are the differences between original package and this package:
1. All the namespace are changed to `DavidNeal/LaravelSesTracker`
2. Service provider is now `LaravelSesTrackerServiceProvider`
3. All the tables name are prefixed with `laravel_ses_tracker` instead of `laravel_ses`
4. New unsubscribe column in `sent_emails` (TODO)
5. Config file is now `config/laravel-ses-tracker.php` instead of `config/laravelses.php`
6. Routes are unchanged, they are still prefixed with `/laravel-ses`

Apart from the above listed differences, there are not much more difference between this package and oliveready7's package.

## Install via composer

Add to composer.json
```
composer require davidneal/laravel-ses-tracker
```
Make sure your app/config/services.php has SES values set

```
'ses' => [
    'key' => your_ses_key,
    'secret' => your_ses_secret,
    'domain' => your_ses_domain,
    'region' => your_ses_region,
],
```

Important to note that if you're using an IAM, it needs access to
SNS (for deliveries, bounces and complaints) as well as SES

Make sure your mail driver located in app/config/mail.php is set to 'ses'

Publish public assets

```
php artisan vendor:publish --tag=public --force
```

Migrate the package's database tables

```
php artisan migrate
```

Optionally you can publish the package's config (laravel-ses-tracker.php)

```
php artisan vendor:publish --tag=config
```

Config Options
- aws_sns_validator - whether the package uses AWS's SNS validator for inbound SNS requests. Default = false

Run command in **production** to setup Amazon email notifications to track bounces, complaints and deliveries. Make sure in your configuration your app URL is set correctly.

If your application uses the http protocol instead of https add the --http flag to this command

```
php artisan setup:sns
```

## Usage

To send an email with all tracking enabled

```
SesMail::enableAllTracking()
    ->to('hello@example.com')
    ->send(new Mailable);
```

All tracking allows you to track opens, bounces, deliveries, complaints and links


You can, of course, disable and enable all the tracking options

```
SesMail::disableAllTracking();
SesMail::disableOpenTracking();
SesMail::disableLinkTracking();
SesMail::disableBounceTracking();
SesMail::disableComplaintTracking();
SesMail::disableDeliveryTracking();


SesMail::enableAllTracking();
SesMail::enableOpenTracking();
SesMail::enableLinkTracking();
SesMail::enableBounceTracking();
SesMail::enableComplaintTracking();
SesMail::enableDeliveryTracking();
```

The setEmailId option gives you the chance to group emails via a foreign id, so you can get the results for a specific email of your own.

```
SesMail::enableAllTracking()
    ->setEmailId($email->id)
    ->to('hello@example.com')
    ->send(new Mailable);
```

You can manipulate the results manually by querying the database. Or you can use functions that come with the package.

```
SesMail::statsForEmail('welcome_emails');

//example result
[
    "send_count" => 8,
    "deliveries" => 7,
    "opens" => 4,
    "bounces" => 1,
    "complaints" => 2,
    "click_throughs" => 3,
    "link_popularity" => collect([
        "https://welcome.page" => [
            "clicks" => 3
        ],
        "https://facebook.com/brand" => [
            "clicks" => 1
        ]
    ])
]
```

Send count = number of emails that were attempted

Deliveries = number of emails that were delivered

Opens = number of emails that were opened

Complaints = number of people that put email into spam

Click throughs = number of people that clicked at least one link in your email

Link Popularity = number of unique clicks on each link in the email, ordered by the most clicked.
