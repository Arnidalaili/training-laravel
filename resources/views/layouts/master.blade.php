
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="{{ asset('libraries/jqgrid/css/jquery-ui.css') }}" />
        <link rel="stylesheet" href="{{ asset('libraries/jqgrid/css/trirand/ui.jqgrid.css') }}" />
        <link rel="stylesheet" href="{{ asset('libraries/themes/redmond/jquery-ui.theme.css') }}" />
        <link rel="stylesheet" href="{{ asset('libraries/themes/redmond/jquery-ui.css') }}" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        
        <script src="{{ asset('libraries/jqgrid/js/trirand/i18n/grid.locale-en.js') }}" ></script>
        <script src="{{ asset('libraries/jqgrid/js/trirand/jquery.jqGrid.min.js') }}" ></script>
        <script src="{{ asset('libraries/jqgrid/jqgridjs/highlight/highlight.js') }}" ></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://unpkg.com/autonumeric"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    </head>

    <body class="hold-transition sidebar-collapse layout-fixed">
        <div class="loader" id="loader">
            <span>Loading</span>
        </div>

        <div id="dialog-message" title="Pesan" class="text-center text-danger" style="display: none;">
            <span class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size:25px;"></span>
            <p></p>
        </div>
        <div id="dialog-success-message" title="Pesan" class="text-center text-success" style="display: none;">
            <span class="fa fa-check" aria-hidden="true" style="font-size:25px;"></span>
            <p></p>
        </div>
        <div id="dialog-confirm" title="Pesan" class="text-center " style="display: none;">
            <span class="fa fa-exclamation-triangle text-warning" aria-hidden="true" style="font-size:25px;"></span>
            <p></p>
        </div>

        <!--Modal for report -->
        <div class="modal fade" id="rangeModal" tabindex="-1" aria-labelledby="rangeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal_header align-items-center">
                        <h5 class="modal-title" id="rangeModalLabel">Pilih</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="formRange" target="_blank">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="sidx">
                            <input type="hidden" name="sord">

                            <div class="form-group row">
                                <div class="col-sm-2 col-form-label">
                                    <label for="">Dari</label>
                                </div>
                                <div class="col-sm-10">
                                    <input type="text" name="dari" class="form-control autonumeric-report" autofocus>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-2 col-form-label">
                                    <label for="">Sampai</label>
                                </div>
                                <div class="col-sm-10">
                                    <input type="text" name="sampai" class="form-control autonumeric-report">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Report</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="wrapper">
            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active" style="text-transform: uppercase;">
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <section class="content">
                    @yield('content')
                </section>
            </div>
        </div>
    </body>
</html>
