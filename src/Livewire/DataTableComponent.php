<?php

namespace Yungifez\AprilUI\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Yungifez\AprilUI\Livewire\Columns\Column;

abstract class DataTableComponent extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public ?string $sort = null;

    #[Url]
    public string $direction = 'asc';

    #[Url]
    public int $perPage = 10;

    public array $selected = [];

    public int $tableRevision = 0;

    protected bool $selectable = false;

    protected array $perPageOptions = [10, 25, 50];

    abstract protected function builder(): Builder;

    /** @return array<int, Column> */
    abstract protected function columns(): array;

    protected function primaryKey(): string
    {
        return 'id';
    }

    protected function defaultSort(): ?array
    {
        return null;
    }

    public function mount(): void
    {
        $defaultSort = $this->defaultSort();
        $this->sort ??= $defaultSort['field'] ?? null;
        $this->direction = $defaultSort['direction'] ?? $this->direction;
        $this->perPage = in_array($this->perPage, $this->perPageOptions, true) ? $this->perPage : $this->perPageOptions[0];
    }

    public function updateTable(array $query): void
    {
        $nextSearch = (string) ($query['search'] ?? '');
        $nextPerPage = (int) ($query['perPage'] ?? $this->perPage);
        $nextSort = data_get($query, 'sort.key');
        $nextDirection = data_get($query, 'sort.direction') === 'desc' ? 'desc' : 'asc';
        $column = $this->findColumn($nextSort);
        $nextSort = $column?->isSortable() ? $nextSort : null;
        $nextPerPage = in_array($nextPerPage, $this->perPageOptions, true) ? $nextPerPage : $this->perPageOptions[0];
        $stateChanged = [$this->search, $this->sort, $this->direction, $this->perPage] !== [$nextSearch, $nextSort, $nextDirection, $nextPerPage];

        $this->search = $nextSearch;
        $this->sort = $nextSort;
        $this->direction = $nextDirection;
        $this->perPage = $nextPerPage;
        $stateChanged ? $this->resetPage() : $this->setPage((int) ($query['page'] ?? 1));
        $this->tableRevision++;
    }

    public function updateSelection(array $selected): void
    {
        $this->selected = array_values(array_unique(array_map('strval', $selected)));
    }

    public function render()
    {
        $rows = $this->rows();

        /** @var view-string $view */
        $view = 'april::livewire.data-table';

        return view($view, [
            'columns' => $this->columnDefinitions(),
            'data' => $this->serializeRows($rows->getCollection()),
            'pagination' => [
                'mode' => 'controlled',
                'page' => $rows->currentPage(),
                'perPage' => $rows->perPage(),
                'total' => $rows->total(),
                'search' => $this->search,
                'sort' => $this->sort ? ['key' => $this->sort, 'direction' => $this->direction] : null,
            ],
            'id' => $this->tableId(),
            'perPageOptions' => $this->perPageOptions,
            'rowKey' => $this->primaryKey(),
            'selectable' => $this->selectable,
            'searchable' => collect($this->columns())->contains(fn (Column $column) => $column->isSearchable()),
        ]);
    }

    protected function rows(): LengthAwarePaginator
    {
        $query = clone $this->builder();
        $searchableColumns = array_filter($this->columns(), fn (Column $column) => $column->isSearchable());

        if ($this->search !== '' && $searchableColumns !== []) {
            $query->where(function (Builder $query) use ($searchableColumns): void {
                foreach ($searchableColumns as $column) {
                    $column->applySearch($query, $this->search);
                }
            });
        }

        if ($column = $this->findColumn($this->sort)) {
            $this->sortBeforeBuilderOrder($query, $column);
        }

        return $query->paginate($this->perPage);
    }

    /**
     * Apply the chosen sort ahead of any order the builder already has.
     *
     * The builder's order then only breaks ties. Appended after it, the
     * chosen sort would change nothing whenever the builder orders rows.
     */
    protected function sortBeforeBuilderOrder(Builder $query, Column $column): void
    {
        $base = $query->getQuery();
        $builderOrders = $base->orders ?? [];
        $builderBindings = $base->bindings['order'];

        $base->orders = null;
        $base->bindings['order'] = [];

        $column->applySort($query, $this->direction);

        $base->orders = [...($base->orders ?? []), ...$builderOrders];
        $base->bindings['order'] = [...$base->bindings['order'], ...$builderBindings];
    }

    /** @return array<int, array<string, mixed>> */
    protected function columnDefinitions(): array
    {
        return array_map(fn (Column $column) => $column->toArray(), $this->columns());
    }

    /** @return array<int, array<string, mixed>> */
    protected function serializeRows(Collection $rows): array
    {
        return $rows->map(fn ($row) => method_exists($row, 'toArray') ? $row->toArray() : (array) $row)->values()->all();
    }

    protected function findColumn(?string $field): ?Column
    {
        return collect($this->columns())->first(fn (Column $column) => $column->field() === $field);
    }

    protected function tableId(): string
    {
        return str(static::class)->replace('\\', '-')->lower()->toString();
    }
}
