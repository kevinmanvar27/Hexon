<?php

namespace App\DataTables;

use App\Models\DeliveryChallan;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DeliveryChallansDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<DeliveryChallan> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('first_name', function (DeliveryChallan $delivery_challan) {
                return $delivery_challan->customer?->first_name ?? '-';
            })
            ->filterColumn('first_name', function ($query, $keyword) {
                $query->whereHas('customer', function ($q) use ($keyword) {
                    $q->where('first_name', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('name', function (DeliveryChallan $delivery_challan) {
                return $delivery_challan->user?->name ?? '-';
            })
            ->addColumn('items_count', function (DeliveryChallan $delivery_challan) {
                return $delivery_challan->items_count;
            })
            ->addColumn('receive_status', function(DeliveryChallan $po) {
                $hasPending = $po->items->contains(fn($item) => $item->remaining_quantity != 0);
                return $hasPending ? 'Pending' : 'All Received';
            })
            ->editColumn('created_at', function(DeliveryChallan $delivery_challan) {
                return $delivery_challan->created_at ? $delivery_challan->created_at->format('d M Y') : '';
            })
            ->addColumn('action', function(DeliveryChallan $delivery_challan) {
                    $user = auth()->user();

                    $hasPending =  $delivery_challan->items->contains(fn($item) => $item->remaining_quantity != 0);

                    $btn = '<div class="btn-group" role="group">';
                    
                    if ($hasPending) {
                        $btn .= '<a href="' . route('deliveryChallans.receive',  $delivery_challan->id) . '" class="btn btn-sm btn-warning me-2">
                                    <i class="fa fa-box-open"></i> Receive</a>';
                    }

                    $btn .= '<a href="' . route('deliveryChallans.download',  $delivery_challan->id) . '" class="btn btn-sm btn-success me-2">
                                <i class="fa fa-download"></i> PDF</a>';

                    if ($user->can('deliveryChallans-list')) {
                        $btn .= '<button type="button" 
                                    class="btn btn-sm btn-info me-2 showdeliveryChallan"
                                    data-id="'. $delivery_challan->id.'">
                                    <i class="fa fa-eye"></i>
                                </button>';
                    }
                    if ($user->can('deliveryChallans-edit')) {
                        $btn .= '<a href="'.route('deliveryChallans.edit',  $delivery_challan->id).'" class="btn btn-sm btn-primary me-2">
                                    <i class="fa fa-edit"></i></a>';
                    }
                    if ($user->can('deliveryChallans-delete')) {
                        $btn .= '<form action="'.route('deliveryChallans.destroy',  $delivery_challan->id).'" method="POST" style="display:inline-block;">
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
     * @return QueryBuilder<DeliveryChallan>
     */
    public function query(DeliveryChallan $model): QueryBuilder
    {
        return $model->newQuery()
                    ->with(['customer', 'items', 'user'])
                    ->withCount('items');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('deliverychallans-table')
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

            Column::make('po_no'),

            Column::make('first_name')
                ->title('Client Name'),

            Column::make('items_count')
                ->title('Total Items')
                ->searchable(false)
                ->orderable(true),

            Column::make('name')
                ->title('Prepared By'),

            Column::make('receive_status')
                ->title('Receive Status')
                ->searchable(false)
                ->orderable(false),

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
        return 'DeliveryChallans_' . date('YmdHis');
    }
}
