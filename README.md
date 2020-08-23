# Laravel Firebase Sync
## Synchronize your Eloquent models with the [Firebase Realtime Database](https://firebase.google.com/docs/database/)

## Contents
- [Installation](#installation)
- [Usage](#usage)


## Installation
In order to add Laravel Firebase Sync to your project, just add

    "quanvt/laravel-firebase-sync": "^1.0"

to your composer.json. Then run `composer install` or `composer update`.

Or run `composer require quanvt/laravel-firebase-sync ` if you prefer that.

## Usage

### Configuration
This package requires firebase config `config/firebase.json` file:

or you can add the following section to your `config/services.php` file:

```php
'firebase' => [
    "type" => "service_account",
    "project_id" => "your_firebase_project_id",
    "private_key_id" => "your_firebase_private_key_id",
    "private_key" => "your_firebase_private_key",
    "client_id" => "your_firebase_client_id",
    "auth_uri" => "your_firebase_auth_uri",
    "token_uri" => "your_firebase_token_uri",
    "auth_provider_x509_cert_url" => "your_firebase_auth_provider_x509_cert_url",
    "client_x509_cert_url" => "your_firebase_client_x509_cert_url",
    "database_url" => "your_firebase_database_url"
]
```

### Synchronizing models
To synchronize your Eloquent models with the Firebase realtime database, simply let the models that you want to synchronize with Firebase use the `QuanVT\Firebase\SyncWithFirebase` trait.

```php
use QuanVT\Firebase\SyncWithFirebase;

class User extends Model {

    use SyncWithFirebase;

}
```

The data that will be synchronized is the array representation of your model. That means that you can modify the data using the existing Eloquent model attributes like `visible`, `hidden` or `appends`.

If you need more control over the data that gets synchronized with Firebase, you can override the `getFirebaseSyncData` of the `SyncsWithFirebase` trait and let it return the array data you want to send to Firebase.
