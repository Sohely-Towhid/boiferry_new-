<?php

namespace App\Providers;

use Auth;
use Blade;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Studio\Totem\Totem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Schema Unique Fix
         */
        Schema::defaultstringLength(125);

        /**
         * Set pagination to Bootstrap
         */
        Paginator::useBootstrap();

        /**
         * Mobile Number Validator (BD)
         */
        Validator::extend('mobile', function ($attribute, $value, $parameters, $validator) {
            return preg_match("/^(01[0-9]{9}|8801[0-9]{9})$/", $value);
        }, 'Invalid mobile number.');

        /**
         * Old Password Validator
         */
        /*Validator::extend('password', function ($attribute, $value, $parameters, $validator) {
        return Hash::check($value, auth()->user()->password);
        }, 'Incorrect old password.');*/

        /**
         * Word Count
         */
        Validator::extend('min_word', function ($attribute, $value, $parameters, $validator) {
            $validator->addReplacer('min_word',
                function ($message, $attribute, $rule, $parameters) {
                    return \str_replace(':parameter', $parameters[0], $message);
                }
            );
            return (str_word_count($value) >= $parameters[0]);
        }, 'The :attribute cannot be less than :parameter words.');

        Validator::extend('max_word', function ($attribute, $value, $parameters, $validator) {
            $validator->addReplacer('max_word',
                function ($message, $attribute, $rule, $parameters) {
                    return \str_replace(':parameter', $parameters[0], $message);
                }
            );
            return (str_word_count($value) <= $parameters[0]);
        }, 'The :attribute cannot be more than :parameter words.');

        /**
         * Money directive for Blade
         */
        Blade::directive('money', function ($money) {
            return "<?php echo number_format($money, 2); ?>";
        });

        /**
         * Money directive for Blade
         */
        Blade::directive('money_nz', function ($money) {
            return "<?php echo number_format($money, 0); ?>";
        });

        // Blade::component('blade-input', Input::class);
        Blade::componentNamespace('App\\View\\Components\\Forms', 'form');

        /**
         * Model Macro for Search
         */
        Builder::macro('whereLike', function ($attributes, string $searchTerm) {
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->when(
                        str_contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm) {
                            [$relationName, $relationAttribute] = explode('.', $attribute);

                            $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $searchTerm) {
                                $query->where($relationAttribute, 'LIKE', "%{$searchTerm}%");
                            });
                        },
                        function (Builder $query) use ($attribute, $searchTerm) {
                            $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                        }
                    );
                }
            });
            return $this;
        });

        Totem::auth(function ($request) {
            return (Auth::user()->role == 'admin') ? true : false;
        });
    }
}
