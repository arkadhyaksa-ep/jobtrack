<?php

namespace Tests\Feature;

use App\Models\JobApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Tests\TestCase;

class JobApplicationSummaryTest extends TestCase
{
    use RefreshDatabase;

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_summary_counts_all_applications_by_status(): void
    {
        $this->makeApplication('Wishlist');
        $this->makeApplication('Wishlist');
        $this->makeApplication('Applied');
        $this->makeApplication('Interview');
        $this->makeApplication('Offer');
        $this->makeApplication('Offer');
        $this->makeApplication('Rejected');
        $this->makeApplication('Withdrawn');

        $this->get(route('applications.index'))
            ->assertOk()
            ->assertSee('data-stat="total" data-count="8"', false)
            ->assertSee('data-stat="interview" data-count="1"', false)
            ->assertSee('data-stat="offer" data-count="2"', false)
            ->assertSee('data-stat="rejected" data-count="1"', false);
    }

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_summary_counts_are_unchanged_by_search_filter(): void
    {
        $this->makeApplication('Interview', 'Matching Company');
        $this->makeApplication('Offer', 'Another Company');
        $this->makeApplication('Rejected', 'Third Company');

        $this->get(route('applications.index', ['search' => 'Matching']))
            ->assertOk()
            ->assertSee('Matching Company')
            ->assertDontSee('Another Company')
            ->assertSee('data-stat="total" data-count="3"', false)
            ->assertSee('data-stat="interview" data-count="1"', false)
            ->assertSee('data-stat="offer" data-count="1"', false)
            ->assertSee('data-stat="rejected" data-count="1"', false);
    }

    #[RequiresPhpExtension('pdo_sqlite')]
    public function test_summary_counts_are_unchanged_by_status_filter(): void
    {
        $this->makeApplication('Interview', 'Interview Company');
        $this->makeApplication('Offer', 'Offer Company');
        $this->makeApplication('Rejected', 'Rejected Company');

        $this->get(route('applications.index', ['status' => 'Offer']))
            ->assertOk()
            ->assertSee('Offer Company')
            ->assertDontSee('Interview Company')
            ->assertSee('data-stat="total" data-count="3"', false)
            ->assertSee('data-stat="interview" data-count="1"', false)
            ->assertSee('data-stat="offer" data-count="1"', false)
            ->assertSee('data-stat="rejected" data-count="1"', false);
    }

    private function makeApplication(string $status, ?string $company = null): JobApplication
    {
        static $number = 0;
        $number++;

        return JobApplication::create([
            'company_name' => $company ?? "Company {$number}",
            'job_title' => "Position {$number}",
            'job_url' => null,
            'status' => $status,
            'applied_at' => null,
            'notes' => null,
        ]);
    }
}
