<?php

namespace Tests\Feature;

use App\Models\JobApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Tests\TestCase;

class JobApplicationUpdateTest extends TestCase
{
    use RefreshDatabase;

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_edit_page_shows_existing_values_and_put_method_spoofing(): void
    {
        $application = $this->makeApplication();

        $this->get(route('applications.edit', $application))
            ->assertOk()
            ->assertSee('action="'.route('applications.update', $application).'"', false)
            ->assertSee('name="_method" value="PUT"', false)
            ->assertSee('value="Original Company"', false)
            ->assertSee('value="https://example.test/original-job"', false);
    }

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_valid_update_saves_only_validated_fields_and_redirects_with_success(): void
    {
        $application = $this->makeApplication();

        $response = $this->put(route('applications.update', $application), [
            'company_name' => 'Updated Company',
            'job_title' => 'Senior Laravel Developer',
            'job_url' => 'https://example.test/senior-role',
            'status' => 'Interview',
            'applied_at' => '2026-10-12',
            'notes' => 'Interview scheduled.',
            'unvalidated_field' => 'This must not be saved.',
        ]);

        $response->assertRedirect(route('applications.index'));
        $response->assertSessionHas('success', 'Job application updated successfully.');

        $this->assertDatabaseHas('job_applications', [
            'id' => $application->id,
            'company_name' => 'Updated Company',
            'job_title' => 'Senior Laravel Developer',
            'job_url' => 'https://example.test/senior-role',
            'status' => 'Interview',
            'applied_at' => '2026-10-12',
            'notes' => 'Interview scheduled.',
        ]);
        $this->assertDatabaseCount('job_applications', 1);

        $this->get(route('applications.index'))
            ->assertOk()
            ->assertSee('Job application updated successfully.');
    }

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_invalid_update_is_rejected_and_original_record_is_unchanged(): void
    {
        $application = $this->makeApplication();

        $response = $this->from(route('applications.edit', $application))
            ->put(route('applications.update', $application), [
                'company_name' => '',
                'job_title' => 'Updated Developer',
                'job_url' => 'not a URL',
                'status' => 'In Progress',
                'applied_at' => 'not a date',
                'notes' => ['notes must be a string'],
            ]);

        $response->assertRedirect(route('applications.edit', $application));
        $response->assertSessionHasErrors(['company_name', 'job_url', 'status', 'applied_at', 'notes']);

        $this->assertDatabaseHas('job_applications', [
            'id' => $application->id,
            'company_name' => 'Original Company',
            'job_title' => 'Original Developer',
            'status' => 'Applied',
        ]);
    }

    private function makeApplication(): JobApplication
    {
        return JobApplication::create([
            'company_name' => 'Original Company',
            'job_title' => 'Original Developer',
            'job_url' => 'https://example.test/original-job',
            'status' => 'Applied',
            'applied_at' => '2026-10-10',
            'notes' => 'Original notes.',
        ]);
    }
}