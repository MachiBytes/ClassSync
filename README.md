- `composer create-project --prefer-dist laravel/laravel ClassSync`
- `composer require laravel/breeze --dev`
- `composer require rebing/graphql-laravel`
- `php artisan breeze:install api`
- `php artisan install:api`
- `php artisan migrate`

If encountering `personal_access_token 'id' column has no default value` after using UUID in personal_access_token migration, see this post. https://laracasts.com/discuss/channels/general-discussion/laravel-sanctum-after-switching-to-uuid-general-error-1364-field-id-doesnt-have-a-default-value and scroll down to `vagkaefer` solution.

### vagkaefer Solution
1. Create `App\Traits\UsesUuid.php` file and paste the following
    ``` php
    <?php

    namespace App\Traits;
    
    use Illuminate\Support\Str;

    trait UsesUuid
    {

        protected $primaryKey = 'id';

        protected static function bootUsesUuid()
        {
            static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
            });
        }

        public function getIncrementing()
        {
            return false;
        }

        public function getKeyType()
        {
            return 'string';
        }
    }
    ```
2. Create `PersonalAccessToken.php` model and paste the following
    ``` php
    <?php

    namespace App\Models;

    use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
    use App\Traits\UsesUuid;

    class PersonalAccessToken extends SanctumPersonalAccessToken
    {
        use UsesUuid;
    }
    ```
3. Modify the `AppServiceProvider.php` file
    ``` php
    <?php

    namespace App\Providers;

    use Illuminate\Support\ServiceProvider;
    use Laravel\Sanctum\Sanctum;
    use App\Models\PersonalAccessToken;

    class AppServiceProvider extends ServiceProvider
    {
        /**
        * Register any application services.
        */
        public function register(): void
        {
        
        }

        /**
        * Bootstrap any application services.
        */
        public function boot(): void
        {
            Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        }
    }
    ```

### Using AWS SDK for PHP
- `composer require aws/aws-sdk-php-laravel`
- Put the following in your `.env` file
    ```
    AWS_ACCESS_KEY_ID=your-access-key-id
    AWS_SECRET_ACCESS_KEY=your-secret-access-key
    AWS_DEFAULT_REGION=us-east-1 # or your desired region
    AWS_SES_REGION=us-east-1 # or your SES region
    ```
- `php artisan vendor:publish --provider="Aws\Laravel\AwsServiceProvider"`: This will add an `aws.php` file in your `config` folder. If it doesn't work, there's probably a typo.