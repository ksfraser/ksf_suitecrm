use PHPUnit\Framework\TestCase;
use App\Models\TasksModel;
use App\Models\TasksController;

/**
 * PHPUnit test class for Tasks.
 */
class TasksTest extends TestCase
{
    /**
     * Test the creation of a Task.
     */
    public function testCreateTask()
    {
        $model = new TasksModel(['name' => 'Test Task']);
        $controller = new TasksController($model);

        $controller->createTask();

        $this->assertNotNull($model->get('id'), 'Task ID should not be null after creation.');
        $this->assertEquals('Test Task', $model->get('name'), 'Task name should match the input.');
    }
}