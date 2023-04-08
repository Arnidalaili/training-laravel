<!DOCTYPE html> 
<html>
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Penjualan Report</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('libraries/stimulsoft-report/2021.03.06/css/stimulsoft.viewer.office2013.whiteblue.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('libraries/stimulsoft-report/2021.03.06/css/stimulsoft.designer.office2013.whiteblue.css') }}"/>
        <script type="text/javascript" src="{{ asset('libraries/stimulsoft-report/2021.03.06/scripts/stimulsoft.reports.js') }}"></script>
        <script type="text/javascript" src="{{ asset('libraries/stimulsoft-report/2021.03.06/scripts/stimulsoft.viewer.js') }}"></script>
        <script type="text/javascript" src="{{ asset('libraries/stimulsoft-report/2021.03.06/scripts/stimulsoft.dashboards.js') }}"></script>
        <script type="text/javascript" src="{{ asset('libraries/stimulsoft-report/2021.03.06/scripts/stimulsoft.designer.js') }}"></script>
        
        <script>
            
            function Start()
            {
                Stimulsoft.Base.StiLicense.loadFromFile("{{ asset('libraries/stimulsoft-report/2021.03.06/stimulsoft/license.php') }}");
                var viewer = new Stimulsoft.Viewer.StiViewer(null, "StiViewer", false)
                var report = new Stimulsoft.Report.StiReport()
                
                var options = new Stimulsoft.Designer.StiDesignerOptions()
                var designer = new Stimulsoft.Designer.StiDesigner(options, "Designer", false)
                var dataSet = new Stimulsoft.System.Data.DataSet("Data")
                 
                viewer.renderHtml('content')
                report.loadFile("{{ asset('reports/penjualanDetail.mrt') }}")
                
                report.dictionary.dataSources.clear() 
                 
                dataSet.readJson(<?= json_encode($data) ?>)
                report.regData(dataSet.dataSetName, '', dataSet)
                report.dictionary.synchronize()

                viewer.report = report
                // designer.renderHtml("content")
                // designer.report = report
            }
        </script>
        <style type="text/css">
            .stiJsViewerPage 
            {
                word-break: break-all
            }
        </style>
        
    </head>
    <body onLoad="Start()" onafterprint="afterPrint()">
        <div id="content"></div>
    </body>
</html>