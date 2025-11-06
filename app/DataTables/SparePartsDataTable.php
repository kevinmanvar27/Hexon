<?php

namespace App\DataTables;

use App\Models\SpareParts;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SparePartsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<SpareParts> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))            
            ->addIndexColumn()
            ->editColumn('created_at', function(SpareParts $spare_part) {
                return $spare_part->created_at ? $spare_part->created_at->format('d M Y') : '';
            })
            ->addColumn('action', function(SpareParts $spare_part) {
                $user = auth()->user();
                $btn = '<div class="btn-group" role="group">';
            
                if ($user && $user->can('part-list')) {
                    $btn .=  '<button type="button" 
                                class="btn btn-sm btn-info me-2 showPart" 
                                data-id="'.$spare_part->id.'">
                                <i class="fa fa-eye"></i>
                            </button>';
                }
                if ($user && $user->can('part-edit')) {
                    $btn .= '<a href="'.route('spareParts.edit', $spare_part->id).'" class="btn btn-sm btn-primary me-2">
                                <i class="fa fa-edit"></i></a>';
                }
                if ($user && $user->can('part-delete')) {
                    $btn .= '<form action="'.route('spareParts.destroy', $spare_part->id).'" method="POST" style="display:inline-block;">
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
     * @return QueryBuilder<SpareParts>
     */
    public function query(SpareParts $model): QueryBuilder
    {
        return $model->newQuery(); 
    }


    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('spareparts-table')
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

            Column::make('name'),
            Column::make('type'),
            Column::make('size'),
            Column::make('weight'),
            Column::make('qty'),
            Column::make('rate'),
            Column::make('created_at'),
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
        return 'SpareParts_' . date('YmdHis');
    }
}
