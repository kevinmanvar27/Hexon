<?php

namespace App\DataTables;

use App\Models\Rejection;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RejectionsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Rejection> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('created_at', function(Rejection $rejection) {
                return $rejection->created_at ? $rejection->created_at->format('d M Y') : '';
            })
            ->addColumn('spare_part', function (Rejection $rejection) {
                return $rejection->sparePart ? $rejection->sparePart->name : '-';
            })
            ->addColumn('action', function (Rejection $rejection) {
                $user = auth()->user();
                // Edit and Delete buttons
                $btn = '<div class="btn-group" role="group">';
                if ($user->can('internalRejections-edit')) {
                    $btn .= '<a href="'.route('rejections.edit', $rejection->id).'" class="btn btn-sm btn-primary me-1">
                                    <i class="fa fa-edit"></i>
                                </a>';
                }
                if ($user->can('internalRejections-delete')) {
                    $btn .= '<form action="'.route('rejections.destroy', $rejection->id).'" 
                    method="POST" style="display:inline-block;">
                    '.csrf_field().method_field("DELETE").'
                    <button type="submit" class="btn btn-sm btn-danger"
                    onclick="return confirm(\'Are you sure?\')">
                    <i class="fa fa-trash"></i>
                    </button>
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
     * @return QueryBuilder<Rejection>
     */
    public function query(Rejection $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('rejections-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
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

            Column::make('id')->visible(false),
            Column::make('user_id')->title('User Id'),
            Column::make('spare_part')->title('Spare Part'),
            Column::make('qty')->title('Quantity'),
            Column::make('reason')->title('Reason'),
            Column::make('created_at')->title('Created At'),

            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Rejections_' . date('YmdHis');
    }
}