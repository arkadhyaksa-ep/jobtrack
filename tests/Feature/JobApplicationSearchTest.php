<?php

namespace Tests\Feature;

use App\Models\JobApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Tests\TestCase;

class JobApplicationSearchTest extends TestCase
{
    use RefreshDatabase;

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_search_matches_company_name_partially(): void
    {
        $this->makeApplication('Northwind Systems', 'Support Specialist', 'Applied');
        $this->makeApplication('Contoso', 'Account Manager', 'Wishlist');

        $this->get(route('applications.index', ['search' => 'wind']))
            ->assertOk()
            ->assertSee('Northwind Systems')
            ->assertDontSee('Contoso');
    }

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_search_matches_job_title_partially(): void
    {
        $this->makeApplication('Northwind', 'Senior Laravel Engineer', 'Applied');
        $this->makeApplication('Contoso', 'Account Manager', 'Wishlist');

        $this->get(route('applications.index', ['search' => 'Laravel']))
            ->assertOk()
            ->assertSee('Senior Laravel Engineer')
            ->assertDontSee('Account Manager');
    }

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_status_filter_returns_only_matching_applications(): void
    {
        $this->makeApplication('Applied Company', 'Developer', 'Applied');
        $this->makeApplication('Wishlist Company', 'Designer', 'Wishlist');

        $this->get(route('applications.index', ['status' => 'Applied']))
            ->assertOk()
            ->assertSee('Applied Company')
            ->assertDontSee('Wishlist Company')
            ->assertSee('value="Applied" selected', false);
    }

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_search_and_status_filters_are_applied_together(): void
    {
        $this->makeApplication('Acme North', 'Backend Developer', 'Applied');
        $this->makeApplication('Acme South', 'Backend Developer', 'Wishlist');
        $this->makeApplication('Other Company', 'Backend Developer', 'Applied');

        $this->get(route('applications.index', ['search' => 'Acme', 'status' => 'Applied']))
            ->assertOk()
            ->assertSee('Acme North')
            ->assertDontSee('Acme South')
            ->assertDontSee('Other Company');
    }

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_invalid_status_filter_is_ignored(): void
    {
        $this->makeApplication('Applied Company', 'Developer', 'Applied');
        $this->makeApplication('Wishlist Company', 'Designer', 'Wishlist');

        $this->get(route('applications.index', ['status' => 'Unknown']))
            ->assertOk()
            ->assertSee('Applied Company')
            ->assertSee('Wishlist Company')
            ->assertSee('All statuses');
    }

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_pagination_links_preserve_active_filters(): void
    {
        for ($number = 1; $number <= 11; $number++) {
            $this->makeApplication("Acme {$number}", "Engineer {$number}", 'Applied');
        }

        $this->get(route('applications.index', ['search' => 'Engineer', 'status' => 'Applied']))
            ->assertOk()
            ->assertSee('search=Engineer', false)
            ->assertSee('status=Applied', false)
            ->assertSee('page=2', false);
    }

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_no_matching_results_have_a_filter_specific_empty_message(): void
    {
        $this->makeApplication('Northwind', 'Engineer', 'Applied');

        $this->get(route('applications.index', ['search' => 'missing']))
            ->assertOk()
            ->assertSee('No job applications match your search or filters.')
            ->assertDontSee('No job applications yet.');
    }

    private function makeApplication(string $company, string $title, string $status): JobApplication
    {
        return JobApplication::create([
            'company_name' => $company,
            'job_title' => $title,
            'job_url' => null,
            'status' => $status,
            'applied_at' => null,
            'notes' => null,
        ]);
    }
}
