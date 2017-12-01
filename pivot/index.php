<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Welcome to Flexmonster Pivot Table and Charts Component</title>
    <link href="flexmonster/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="container">
    <div class="header">
        <div class="headerLogo">
            <a href="http://www.flexmonster.com/" target="_blank"><img src="http://www.flexmonster.com/images/email/logo.svg" height="40" border="0" /></a>
        </div>
    </div>

    <!-- 1. Create a DIV container to insert the component -->
    <div id="pivotContainer" style="padding-bottom: 30px;"></div>

    <!-- 2. Include flexmonster.js file -->
    <script type="text/javascript" src="flexmonster/flexmonster.js"></script>

    <!-- 3. Create an instance of the component using new Flexmonster() -->
    <script type="text/javascript">

        var pivot = new Flexmonster({
            container: "pivotContainer",
            toolbar: true,
            report: {
                dataSource: {
                    dataSourceType: "ocsv",
                    filename: "http://localhost/visualizer/pivot/data/process.php"
                },
                slice: {
                    rows: [
                        { uniqueName: "Country" },
                        { uniqueName: "Category" },
                        { uniqueName: "[Measures]" }
                    ],
                    columns: [
                        { uniqueName: "Color" }
                    ],
                    measures: [
                        { uniqueName: "Price", aggregation: "sum", format: "currency"},
                        { uniqueName: "Discount", aggregation: "average"}
                    ]
                }
            },
            licenseKey: "Z76M-XAJ268-1Z1D61-401L4B"
        });

    </script>
    <br/>
</div>
</body>
</html>
