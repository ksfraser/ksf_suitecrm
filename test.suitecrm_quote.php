use PHPUnit\Framework\TestCase;
use App\Models\QuotesModel;
use App\Models\QuotesController;

/**
 * PHPUnit test class for Quotes.
 */
class QuotesTest extends TestCase
{
    /**
     * Test the creation of a Quote.
     */
    public function testCreateQuote()
    {
        $model = new QuotesModel(['name' => 'Test Quote']);
        $controller = new QuotesController($model);

        $controller->createQuote();

        $this->assertNotNull($model->get('id'), 'Quote ID should not be null after creation.');
        $this->assertEquals('Test Quote', $model->get('name'), 'Quote name should match the input.');
    }
}