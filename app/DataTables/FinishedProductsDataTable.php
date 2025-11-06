<?php

namespace App\DataTables;

use App\Models\FinishedProduct;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FinishedProductsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<FinishedProduct> $query
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()

            // Product name via relationship
            ->addColumn('product_name', function (FinishedProduct $finishedProduct) {
                return $finishedProduct->product?->name ?? '-';
            })

            // Created by user name
            ->addColumn('created_by_name', function (FinishedProduct $finishedProduct) {
                return $finishedProduct->createdBy?->name ?? '-';
            })

            ->editColumn('created_at', function (FinishedProduct $finishedProduct) {
                return $finishedProduct->created_at
                    ? $finishedProduct->created_at->format('d M Y')
                    : '';
            })

            ->addColumn('action', function (FinishedProduct $finishedProduct) {
                $user = auth()->user();
                $btn = '<div class="btn-group" role="group">';

                if ($user->can('finished-product-list')) {
                    $btn .= '<button type="button" 
                                class="btn btn-sm btn-info me-2 showFinishedProduct" 
                                data-id="'.$finishedProduct->id.'">
                                <i class="fa fa-eye"></i>
                            </button>';
                }
                if ($user->can('finished-product-delete')) {
                    $btn .= '<form action="'.route('finishedProducts.destroy', $finishedProduct->id).'" 
                                method="POST" style="display:inline-block;">
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
     * @return QueryBuilder<FinishedProduct>
     */
    public function query(FinishedProduct $model): QueryBuilder
    {
        // eager load product + createdBy to avoid N+1 queries
        return $model->newQuery()->with(['product', 'createdBy']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('finishedproducts-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'desc')
            ->selectStyleSingle()
            ->scrollX(true)
            ->autoWidth(false)
            ->dom("<'row mb-3'<'col-12 col-md-4 mb-2 mb-md-0'l>
                <'col-12 col-md-4 text-center mb-2 mb-md-0'B>
                <'col-12 col-md-4'f>>" ."tr" .
                "<'row mt-3'<'col-sm-6'i><'col-sm-6'p>>")
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')
                ->title('No')
                ->searchable(false)
                ->orderable(false)
                ->width(40),

            Column::make('product_name')->title('Product Name'),
            Column::make('qty')->title('Quantity'),
            Column::make('created_by_name')->title('Created By'),
            Column::make('created_at')->title('Created At'),

            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-center')
                ->title('Action'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'FinishedProducts_' . date('YmdHis');
    }
}
