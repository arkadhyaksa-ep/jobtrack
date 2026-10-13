<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Tests\TestCase;

class JobApplicationCreationTest extends TestCase
{
    use RefreshDatabase;

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_valid_application_is_saved_and_redirects_with_a_success_message(): void
    {
        $response = $this->post(route('applications.store'), [
            'company_name' => 'Example Company',
            'job_title' => 'Laravel Developer',
            'job_url' => 'https://example.test/jobs/laravel-developer',
            'status' => 'Applied',
            'applied_at' => '2026-10-13',
            'notes' => 'Follow up next week.',
            'unvalidated_field' => 'This must not be stored.',
        ]);

        $response->assertRedirect(route('applications.index'));
        $response->assertSessionHas('success', 'Job application added successfully.');

        $this->assertDatabaseHas('job_applications', [
            'company_name' => 'Example Company',
            'job_title' => 'Laravel Developer',
            'job_url' => 'https://example.test/jobs/laravel-developer',
            'status' => 'Applied',
            'applied_at' => '2026-10-13',
            'notes' => 'Follow up next week.',
        ]);

        $this->assertDatabaseCount('job_applications', 1);

        $this->get(route('applications.index'))
            ->assertOk()
            ->assertSee('Job application added successfully.');
    }
}