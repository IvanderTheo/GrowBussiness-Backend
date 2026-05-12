# ai
step 1 = composer require google-gemini-php/laravel
step 2 = php artisan gemini:install
repo = https://github.com/google-gemini-php/laravel
fix ssl install cacert.perm
code :
use Gemini\Enums\ModelVariation;
use Gemini\GeminiHelper;
use Gemini\Laravel\Facades\Gemini;

protected $result;

//helper
public function generate($string) : String
{
    $this->result = Gemini::generativeModel(
        model: GeminiHelper::generateGeminiModel(
            variation: ModelVariation::FLASH,
            generation: 2.5 // models/gemini-2.5-flash
        )
    )->generateContent($string);
    return $this->result->text();//response
}
# schedule
php artisan make:command (name)
php artisan schedule:run //test
php artisan schedule:work //run command

# forum
enum class, App\Enums\ForumCategory

# products
install csv reader: composer require league/csv

# hpp calculation
/count-hpp form request 
{
  "target_production": 1000,
  "variable_costs": [...],
  "fixed_costs": [...]
}
- example
{
    "target_production":1000,
    "variable_costs": [
        {
            "product_name": "Pizza",
            "category": "Makanan & Minuman",
            "material_name": "Tepung",
            "usage_amount": "300",
            "usage_unit": "gram",
            "purchase_total_price": "15000",
            "purchase_quantity": "1000",
            "purchase_unit": "gram",
            "cost_per_product": "4500"
        },
        {
            "product_name": "Pizza",
            "category": "Makanan & Minuman",
            "material_name": "Keju Mozzarella",
            "usage_amount": "150",
            "usage_unit": "gram",
            "purchase_total_price": "85000",
            "purchase_quantity": "1000",
            "purchase_unit": "gram",
            "cost_per_product": "12750"
        },
        {
            "product_name": "Pizza",
            "category": "Makanan & Minuman",
            "material_name": "Sosis",
            "usage_amount": "100",
            "usage_unit": "gram",
            "purchase_total_price": "40000",
            "purchase_quantity": "1000",
            "purchase_unit": "gram",
            "cost_per_product": "4000"
        }
    ],
    "fixed_costs":[
        {
            "category": "Makanan & Minuman",
            "cost_name": "Listrik",
            "total_monthly_cost": "500000"
        },
        {
            "category": "Makanan & Minuman",
            "cost_name": "Gas",
            "total_monthly_cost": "350000"
        },
        {
            "category": "Makanan & Minuman",
            "cost_name": "Gaji Pegawai",
            "total_monthly_cost": "3000000"
        }
    ]
}