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
