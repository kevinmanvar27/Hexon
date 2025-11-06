<?php

namespace App\DataTables;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RolesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Role> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('created_at', function(Role $role) {
                return $role->created_at ? $role->created_at->format('d M Y') : '';
            })
            ->addColumn('action', function(Role $role) {
                $user = auth()->user();
                $btn = '<div class="btn-group" role="group">';
            
                if ($user->can('role-list')) {
                    $btn .=  '<button type="button" 
                                class="btn btn-sm btn-info me-2 showRole" 
                                data-id="'.$role->id.'">
                                <i class="fa fa-eye"></i>
                            </button>';
                }
                if ($user->can('role-edit')) {
                    $btn .= '<a href="'.route('roles.edit', $role->id).'" class="btn btn-sm btn-primary me-2">
                                <i class="fa fa-edit"></i></a>';
                }
                if ($user->can('role-delete')) {
                    $btn .= '<form action="'.route('roles.destroy', $role->id).'" method="POST" style="display:inline-block;">
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
     * @return QueryBuilder<Role>
     */
    public function query(Role $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('roles-table')
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
        return 'Roles_' . date('YmdHis');
    }
}
