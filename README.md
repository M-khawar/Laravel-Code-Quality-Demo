### Auto import Production DB data

Due to this functionality whenever the developer runs migration related commands 

i.e. `php artisan migrate` 

then first the old production database schema will populate along with existing data. Then the internal custom migrations  will process


>Note: There is no need to intensionally upload existed database.

---

#### Cashier:

###### Make Subscription Plans By Seeder
```
php artisan db:seed --class=SubscriptionPlanSeeder
```

###### Webhooks:
```
php artisan cashier:webhook 
php artisan cashier:webhook --disabled
php artisan cashier:webhook --url=<custom_url>
php artisan cashier:webhook --api-version=<version>

