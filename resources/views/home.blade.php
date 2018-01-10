<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>tbot</title>

        <!-- Fonts -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.16/b-1.5.1/b-colvis-1.5.1/b-html5-1.5.1/cr-1.4.1/fc-3.2.4/fh-3.1.3/r-2.2.1/rg-1.0.2/sc-1.4.3/sl-1.2.4/datatables.min.css"/>

        <!-- Styles -->
        <style>
        </style>
    </head>
    <body>
        <div id="app">
            <div class="content">

                <table id="table_id" class="display table table-dark table-striped table-hover table-sm">
                </table>

            </div>
        </div>

        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.16/b-1.5.1/b-colvis-1.5.1/b-html5-1.5.1/cr-1.4.1/fc-3.2.4/fh-3.1.3/r-2.2.1/rg-1.0.2/sc-1.4.3/sl-1.2.4/datatables.min.js"></script>
<script>

$('#table_id').DataTable({
    ajax: {
        url: '/api/v1/exchangeInfo',
        data: function(d) {
            return d;
        }
    }
});

</script>

    </body>
</html>
