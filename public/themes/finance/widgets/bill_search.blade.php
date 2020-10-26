<div class="layui-row mb10">
    <div class="layui-inline">
        <select name="airport_id" class="layui-select search_key">
            <option value="">{{ trans('airport.name') }}</option>
            @foreach($airports as $key => $airport)
                <option value="{{ $airport['id'] }}">{{ $airport['name'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="layui-inline">
        <select name="supplier_id" class="layui-select search_key">
            <option value="">{{ trans('supplier.name') }}</option>
            @foreach($suppliers as $key => $supplier)
                <option value="{{ $supplier['id'] }}">{{ $supplier['name'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="layui-inline">
        <select name="airline_id" class="layui-select search_key">
            <option value="">{{ trans('airline.name') }}</option>
            @foreach($airlines as $key => $airline)
                <option value="{{ $airline['id'] }}">{{ $airline['name'] }}</option>
            @endforeach
        </select>
    </div>
</div>