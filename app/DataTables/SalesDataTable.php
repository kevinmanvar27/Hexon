<?php

namespace App\DataTables;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SalesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Sale> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
                ->addIndexColumn()
                ->addColumn('first_name', function (Sale $sale) {
                    return $sale->customer?->first_name ?? '-';
                })
                ->filterColumn('first_name', function ($query, $keyword) {
                    $query->whereHas('customer', function ($q) use ($keyword) {
                        $q->where('first_name', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('items_count', function (Sale $sale) {
                    return $sale->items_count;
                })
                ->addColumn('action', function(Sale $sale) {
                    $user = auth()->user();

                    $btn = '<div class="btn-group" role="group">';

                    if (strtolower(trim($sale->status)) == 'completed') {
                        $btn .= '<a href="' . route('sales.download', $sale->id) . '" class="btn btn-sm btn-success me-2">
                                    <i class="fa fa-download"></i> PDF</a>';
                    }

                    if ($user->can('sales-list')) {
                        $btn .= '<button type="button" 
                                    class="btn btn-sm btn-info me-2 showSale" 
                                    data-id="'.$sale->id.'">
                                    <i class="fa fa-eye"></i>
                                </button>';
                    }
                    if ($user->can('sales-edit')) {
                        $btn .= '<a href="'.route('sales.edit', $sale->id).'" class="btn btn-sm btn-primary me-2">
                                    <i class="fa fa-edit"></i></a>';
                    }
                    if ($user->can('sales-delete')) {
                        $btn .= '<form action="'.route('sales.destroy', $sale->id).'" method="POST" style="display:inline-block;">
                                    '.csrf_field().method_field("DELETE").'
                                    <button type="submit" class="btn btn-sm btn-danger me-2" 
                                        onclick="return confirm(\'Are you sure?\')">
                                        <i class="fa fa-trash"></i></button>
                                </form>';
                    }
                
                    $btn .= '</div>';
                    return $btn;
                })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Sale>
     */
    public function query(Sale $model): QueryBuilder
    {
        return $model->newQuery()
                        ->with(['customer', 'items'])
                        ->withCount('items');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('sales-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1 ,'desc')
                    ->selectStyleSingle()
                    ->scrollX(true)
                    ->autoWidth(false)
                    ->dom("<'row mb-3'<'col-12 col-md-4 mb-2 mb-md-0'l>
                        <'col-12 col-md-4 text-center mb-2 mb-md-0'B>
                        <'col-12 col-md-4'f>>" ."t" .
                            "<'row mt-3'<'col-sm-6'i><'col-sm-6'p>>")
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
                Column::computed('DT_RowIndex')
                    ->title('S.No')
                    ->exportable(false)
                    ->printable(true)
                    ->orderable(false)
                    ->width(60)
                    ->addClass('text-center'),
                    
                Column::make('id')
                    ->visible(false)
                    ->orderable(true),
                Column::make('create_date'),
                Column::make('invoice'),
                Column::make('first_name')
                    ->title('Client Name'),
                Column::make('balance')->title('Amount'), 

                Column::make('items_count')
                    ->title('Total Items')
                    ->searchable(false)
                    ->orderable(true),

                Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
                    ->width(200)
                    ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Sales_' . date('YmdHis');
    }
}
