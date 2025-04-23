use PHPUnit\Framework\TestCase;
use App\Models\CloneSiteModel;
use App\Controllers\CloneSiteController;

/**
 * PHPUnit test class for CloneSite.
 */
class CloneSiteTest extends TestCase
{
    /**
     * Test the initialization of CloneSiteModel.
     */
    public function testModelInitialization()
    {
        $config = [
            'site_url' => 'https://example.com/siteA',
            'site_url2' => 'https://example.com/siteB',
            'appname' => 'TestApp',
            'soapuser' => 'adminA',
            'soapuser2' => 'adminB',
            'pass_hash' => md5('passwordA'),
            'pass_hash2' => md5('passwordB'),
            'testing' => true,
            'authoritative' => 'A',
            'email_address' => 'admin@example.com',
            'delete_on_clone' => false
        ];

        $model = new CloneSiteModel($config);

        $this->assertInstanceOf(CloneSiteModel::class, $model);
        $this->assertTrue($model->testing);
        $this->assertEquals('A', $model->authoritative);
    }

    /**
     * Test the run method of CloneSiteController.
     */
    public function testControllerRun()
    {
        $config = [
            'site_url' => 'https://example.com/siteA',
            'site_url2' => 'https://example.com/siteB',
            'appname' => 'TestApp',
            'soapuser' => 'adminA',
            'soapuser2' => 'adminB',
            'pass_hash' => md5('passwordA'),
            'pass_hash2' => md5('passwordB'),
            'testing' => true,
            'authoritative' => 'A',
            'email_address' => 'admin@example.com',
            'delete_on_clone' => false
        ];

        $model = new CloneSiteModel($config);
        $controller = new CloneSiteController($model);

        $this->assertInstanceOf(CloneSiteController::class, $controller);

        // Mock the run method to avoid actual SOAP calls.
        $this->expectNotToPerformAssertions();
        $controller->run(['Accounts', 'Contacts']);
    }
}