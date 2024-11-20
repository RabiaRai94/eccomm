<script>
    let dataTable = null;
    $(document).ready(function() {

    });
    function setDataTable(datatable)
    {
        dataTable = datatable;
    }
    function initSelect2ByParams(params)
    {
        $(params.selector).select2({
            placeholder: params.placeholder,
            minimumResultsForSearch: params.hasOwnProperty('minimumResultsForSearch') ? params.minimumResultsForSearch : Infinity,
            allowClear: params.allowClear,
        }).on('change', function(e) {
            if(params.hasOwnProperty('validate'))
            {
                params.validate();
            }
            else
            {
                dataTable.draw();
            }
        });
    }
    function select2ForRoles(params)
    {
        $(params.selector).select2({
            placeholder: params.placeholder,
            allowClear: true,
            ajax: {
                url: "{{ route('users.select2.list') }}",
                dataType: 'json',
                delay: 250,
                data: function(searchParams) {
                    let ajaxData = {
                        search: searchParams.term,
                        page: searchParams.page || 1
                    };
                    ajaxData[params.role] = true
                    return ajaxData;
                },
                processResults: function(data) {
                    let result = {
                        results: $.map(data.data, function(item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        }),
                        pagination: {
                            more: (data.current_page < data.last_page)
                        }
                    };
                    return result;
                },
                cache: true
            }
        }).on('change', function(e) {
            if(params.hasOwnProperty('validate'))
            {
                params.validate();
            }
            else
            {
                dataTable.draw();
            }
        });
    }
    function select2ForLocation(params)
    {
        $(params.selector).select2({
            placeholder: params.placeholder,
            allowClear: true,
            // tags: true,
            ajax: {
                url: "{{ route('locations.select2.list') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function(data) {
                    let result = {
                        results: $.map(data.data, function(item) {
                            return {
                                text: item.name,
                                id: item.id,
                            }
                        }),
                        pagination: {
                            more: (data.current_page < data.last_page)
                        }
                    };
                    return result;
                },
                cache: true
            }
        }).on('change', function(e) {
            if(params.hasOwnProperty('validate'))
            {
                params.validate();
            }
            else
            {
                dataTable.draw();
            }
        });
    }

    function initDateTimePicker(params)
    {
        $(params.id).daterangepicker({
                autoUpdateInput: false,
                timePicker: true,
                singleDatePicker: true,
                timePicker24Hour: false,
                timePickerIncrement: 1,
                locale: {
                    format: 'M/DD/YYYY hh:mm A'
                }
            });
            $(params.id).on('apply.daterangepicker', function(ev, picker) {
                params.validate();
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.startDate.format(
                    'hh:mm A'));
            });
            $(params.id).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
    }
    
</script>
