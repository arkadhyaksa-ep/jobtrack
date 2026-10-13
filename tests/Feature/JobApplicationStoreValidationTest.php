<?php

namespace Tests\Feature;

use Tests\TestCase;

class JobApplicationStoreValidationTest extends TestCase
{
    public function test_create_page_renders_safely_with_invalid_array_old_input(): void
    {
        $this->withSession(['_old_input' => [
            'company_name' => ['invalid'],
            'job_title' => ['invalid'],
            'job_url' => ['invalid'],
            'applied_at' => ['invalid'],
            'notes' => ['invalid'],
        ]])
            ->get(route('applications.create'))
            ->assertOk()
            ->assertSee(route('applications.store'), false)
            ->assertSee('name="company_name"', false);
    }

    public function test_company_name_and_job_title_are_required(): void
    {
        $response = $this->from(route('applications.create'))
            ->post(route('applications.store'), ['status' => 'Wishlist']);

        $response->assertRedirect(route('applications.create'));
        $response->assertSessionHasErrors(['company_name', 'job_title']);
    }

    public function test_invalid_fields_are_rejected_and_old_input_is_kept(): void
    {
        $response = $this->from(route('applications.create'))
            ->post(route('applications.store'), [
                'company_name' => 'Example Company',
                'job_title' => 'Developer',
                'job_url' => 'not a URL',
                'status' => 'In Progress',
                'applied_at' => 'not a date',
                'notes' => ['notes must be a string'],
            ]);

        $response->assertRedirect(route('applications.create'));
        $response->assertSessionHasErrors(['job_url', 'status', 'applied_at', 'notes']);
        $response->assertSessionHasInput('company_name', 'Example Company');
        $response->assertSessionHasInput('job_url', 'not a URL');
    }

    public function test_text_fields_and_job_url_cannot_exceed_255_characters(): void
    {
        $response = $this->from(route('applications.create'))
            ->post(route('applications.store'), [
                'company_name' => str_repeat('c', 256),
                'job_title' => str_repeat('t', 256),
                'job_url' => 'https://example.test/'.str_repeat('a', 236),
                'status' => 'Wishlist',
                'applied_at' => '2026-10-13',
                'notes' => 'Long-field validation.',
            ]);

        $response->assertRedirect(route('applications.create'));
        $response->assertSessionHasErrors(['company_name', 'job_title', 'job_url']);
    }
}