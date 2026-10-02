<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Yungifez\AprilUI\Livewire\Columns\Column;
use Yungifez\AprilUI\Livewire\DataTableComponent;

class SortedTableExam extends Model
{
    protected $table = 'sorted_table_exams';

    protected $guarded = [];

    public $timestamps = false;
}

class SortedTableComponent extends DataTableComponent
{
    protected function builder(): Builder
    {
        return SortedTableExam::query()->orderByDesc('starts_on');
    }

    protected function columns(): array
    {
        return [Column::make('Name', 'name')->sortable()];
    }

    public function shownRows(): Collection
    {
        return $this->rows()->getCollection();
    }

    /** @return array<int, string> */
    public function names(): array
    {
        return $this->shownRows()->pluck('name')->all();
    }
}

beforeEach(function () {
    Schema::create('sorted_table_exams', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->date('starts_on');
    });

    SortedTableExam::query()->insert([
        ['name' => 'Biology', 'starts_on' => '2026-09-01'],
        ['name' => 'Algebra', 'starts_on' => '2026-09-03'],
        ['name' => 'Civics', 'starts_on' => '2026-09-02'],
        ['name' => 'Algebra', 'starts_on' => '2026-09-05'],
    ]);
});

it('keeps the builder order when nobody chose a sort', function () {
    $table = new SortedTableComponent;

    expect($table->names())->toBe(['Algebra', 'Algebra', 'Civics', 'Biology']);
});

it('puts the chosen sort before the builder order', function () {
    $table = new SortedTableComponent;
    $table->sort = 'name';
    $table->direction = 'desc';

    expect($table->names())->toBe(['Civics', 'Biology', 'Algebra', 'Algebra']);
});

it('breaks ties in the chosen sort with the builder order', function () {
    $table = new SortedTableComponent;
    $table->sort = 'name';

    $rows = $table->shownRows();

    expect($rows->pluck('name')->all())->toBe(['Algebra', 'Algebra', 'Biology', 'Civics'])
        ->and($rows->take(2)->pluck('starts_on')->all())->toBe(['2026-09-05', '2026-09-03']);
});
