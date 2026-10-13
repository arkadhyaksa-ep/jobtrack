<?php

namespace Tests\Feature;

use App\Models\JobApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Tests\TestCase;

class JobApplicationDeletionTest extends TestCase
{
    use RefreshDatabase;

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_application_can_be_deleted_and_success_message_is_shown(): void
    {
        $application = JobApplication::create([
            'company_name' => 'Company to Delete',
            'job_title' => 'Laravel Developer',
            'job_url' => 'https://example.test/job',
            'status' => 'Applied',
            'applied_at' => '2026-10-10',
            'notes' => 'A test application.',
        ]);

        $response = $this->delete(route('applications.destroy', $application));

        $response->assertRedirect(route('applications.index'));
        $response->assertSessionHas('success', 'Job application deleted successfully.');
        $this->assertDatabaseMissing('job_applications', ['id' => $application->id]);
        $this->assertDatabaseCount('job_applications', 0);

        $this->get(route('applications.index'))
            ->assertOk()
            ->assertSee('Job application deleted successfully.')
            ->assertSee('No job applications yet.');
    }

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_deleting_a_nonexistent_application_returns_not_found(): void
    {
        $response = $this->delete(route('applications.destroy', 999999));

        $response->assertNotFound();
        $this->assertDatabaseCount('job_applications', 0);
    }
}
