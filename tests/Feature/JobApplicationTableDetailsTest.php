<?php

namespace Tests\Feature;

use App\Models\JobApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Tests\TestCase;

class JobApplicationTableDetailsTest extends TestCase
{
    use RefreshDatabase;

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_job_url_is_rendered_as_a_safe_new_tab_link(): void
    {
        $url = 'https://example.test/careers/role?ref=board&source=jobtrack';
        $this->makeApplication(['job_url' => $url]);

        $this->get(route('applications.index'))
            ->assertOk()
            ->assertSee('View job <span aria-hidden="true">↗</span>', false)
            ->assertSee('href="https://example.test/careers/role?ref=board&amp;source=jobtrack" target="_blank" rel="noopener noreferrer"', false);
    }

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_missing_job_url_displays_an_em_dash(): void
    {
        $this->makeApplication(['job_url' => null]);

        $this->get(route('applications.index'))
            ->assertOk()
            ->assertSee('<span class="table-placeholder" aria-label="No job link">—</span>', false);
    }

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_short_notes_preview_preserves_line_breaks(): void
    {
        $notes = "First line\nSecond line";
        $this->makeApplication(['notes' => $notes]);

        $this->get(route('applications.index'))
            ->assertOk()
            ->assertSee('<span class="notes-preview">First line' . "\n" . 'Second line</span>', false);
    }

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_long_notes_are_truncated_to_about_eighty_characters(): void
    {
        $this->makeApplication(['notes' => str_repeat('A', 100)]);

        $this->get(route('applications.index'))
            ->assertOk()
            ->assertSee('<span class="notes-preview">' . str_repeat('A', 79) . '…</span>', false)
            ->assertDontSee(str_repeat('A', 80), false);
    }

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_empty_and_missing_notes_display_an_em_dash(): void
    {
        $this->makeApplication(['notes' => null]);
        $this->makeApplication(['notes' => '']);

        $this->get(route('applications.index'))
            ->assertOk()
            ->assertSee('<span class="table-placeholder" aria-label="No notes">—</span>', false);
    }

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_html_like_notes_are_escaped_in_the_preview(): void
    {
        $this->makeApplication(['notes' => '<b>Private note</b>']);

        $this->get(route('applications.index'))
            ->assertOk()
            ->assertSee('&lt;b&gt;Private note&lt;/b&gt;', false)
            ->assertDontSee('<b>Private note</b>', false);
    }

    private function makeApplication(array $attributes = []): JobApplication
    {
        return JobApplication::create(array_merge([
            'company_name' => 'Example Company',
            'job_title' => 'Laravel Engineer',
            'job_url' => null,
            'status' => 'Applied',
            'applied_at' => '2026-10-12',
            'notes' => null,
        ], $attributes));
    }
}
