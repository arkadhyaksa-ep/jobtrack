<?php

namespace Tests\Feature;

use App\Models\JobApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Tests\TestCase;

class JobApplicationDateDisplayTest extends TestCase
{
    use RefreshDatabase;

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_application_dates_are_formatted_and_missing_dates_show_an_em_dash(): void
    {
        JobApplication::create([
            'company_name' => 'Northwind',
            'job_title' => 'Engineer',
            'status' => 'Applied',
            'applied_at' => '2026-10-12',
        ]);
        JobApplication::create([
            'company_name' => 'Contoso',
            'job_title' => 'Designer',
            'status' => 'Wishlist',
            'applied_at' => null,
        ]);

        $this->get(route('applications.index'))
            ->assertOk()
            ->assertSee('Oct 12, 2026')
            ->assertSee('—')
            ->assertDontSee('&mdash;');
    }
}
