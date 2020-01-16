
<table lay-filter="airline_bill_item" id="airline_bill_item">
    <tr align="center">
        <td colspan="10" >STATEMENT OF ACCOUNT</td>
    </tr>
    <tr>
    </tr>
    <tr>
        <td >{{ trans('supplier_bill_item.label.flight_date') }}</td>
        <td >{{ trans('supplier_bill_item.label.flight_number') }}</td>
        <td >{{ trans('supplier_bill_item.label.board_number') }}</td>
        <td >{{ trans('supplier_bill_item.label.order_number') }}</td>
        <td >{{ trans('supplier_bill_item.label.num_of_orders') }}</td>
        <td >{{ trans('supplier_bill_item.label.mt') }}</td>
        <td >{{ trans('supplier_bill_item.label.usg') }}</td>
        <td >{{ trans('supplier_bill_item.label.unit') }}</td>
        <td >{{ trans('supplier_bill_item.label.price') }}</td>
        <td >{{ trans('supplier_bill_item.label.total') }}</td>
    </tr>
    @foreach($airline_bill_items as $key => $airline_bill_item)
        <tr>
            <td>{{ $airline_bill_item['flight_date'] }}</td>
            <td>{{ $airline_bill_item['flight_number'] }}</td>
            <td>{{ $airline_bill_item['board_number'] }}</td>
            <td>{{ $airline_bill_item['order_number'] }}</td>
            <td>{{ $airline_bill_item['num_of_orders'] }}</td>
            <td>{{ $airline_bill_item['mt'] }}</td>
            <td>{{ $airline_bill_item['usg'] }}</td>
            <td>{{ $airline_bill_item['unit'] }}</td>
            <td>{{ $airline_bill_item['price'] }}</td>
            <td>{{ $airline_bill_item['total'] }}</td>
        </tr>
    @endforeach
</table>
