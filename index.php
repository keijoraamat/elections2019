
<html>
<head>
    <link rel="stylesheet" href="styling.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript">

        // Load the Visualization API and the piechart package.
        google.charts.load('current', {'packages': ['corechart']});


        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawCandidatesPerParty);
        function drawCandidatesPerParty() {
            var jsonData = $.ajax({
                url: "database/queries/candidates_default_with_independents.php",
                dataType: "json",
                async: false,
                data: {
                    state: belongs_status
                }
            }).responseText;
            var options = {
                is3D: true,
                width: 700,
                height: 500
            }
            // Create our data table out of JSON data loaded from server.
            var data = new google.visualization.DataTable(jsonData);
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(document.getElementById('chart_div_party'));
            chart.draw(data, options);
        }


        google.charts.setOnLoadCallback(drawChartDomains);
        function drawChartDomains() {
            var jsonData = $.ajax({
                url: "database/queries/domains_default_for_listing.php",
                dataType: "json",
                async: false,
                data: {
                    state: domain_status
                }
            }).responseText;
            var options = {
                is3D: true,
                width: 700,
                height: 500
            }
            var data = new google.visualization.DataTable(jsonData);
            var chart = new google.visualization.PieChart(document.getElementById('chart_div_email_domains'));
            chart.draw(data, options);
        }


        google.charts.setOnLoadCallback(drawChartEdu);
        function drawChartEdu() {
            var jsonData = $.ajax({
                url: "database/queries/education.php",
                dataType: "json",
                async: false,
            }).responseText;
            var options = {
                legend: { position: 'top', maxLines: 2},
                seriesType: 'bars',
                width: 900,
                height: 600
            }
            var data = new google.visualization.DataTable(jsonData);

            var chart = new google.visualization.ComboChart(document.getElementById('chart_div_edu'));
            chart.draw(data, options);
        }


        google.charts.setOnLoadCallback(drawChartAges);
        function drawChartAges() {
            var jsonData = $.ajax({
                url: "database/queries/age.php",
                dataType: "json",
                async: false
            }).responseText;

             var options = {
                 title: 'mediaan ja keskmised vanused',
                 hAxis: {title: 'keskmine vanus'},
                 vAxis: {title: 'mediaan vanus'},
                 colorAxis: {colors: ['yellow', 'red']}
             }
            var data = new google.visualization.DataTable(jsonData);

            var chart = new google.visualization.BubbleChart(document.getElementById('chart_div_ages'));
            chart.draw(data, options);
        }


    </script>
</head>

<body>

<div class="row">
    <div class="col-xs-6">
        <div id="chart_div_party"></div>
        <label for="chart_div_party"><h5>Kandidaatide jaotus</h5></label>
        <input type="radio" id="belongs_party" name="candidate_belongs_to" value=1>partei liikmed
        <input type="radio" id="belongs_independent" name="candidate_belongs_to" value=0 checked>nimekirjas kandideerijad
        <hr>
    </div>
    <div class="col-xs-6">
        <div id="chart_div_email_domains"></div>
        <input type="radio" id="domain_party" name="email_domain" value=1>partei liikmed
        <input type="radio" id="domain_independent" name="email_domain" value=0 checked>nimekirjas kandideerijad
        <h3>Emali domeenide populaarsus</h3>
        <hr>
    </div>
</div>
<div class="row">
    <div class="col-xs-6">
        <div id="chart_div_edu"></div>
        <h3>Haridused</h3>
        <hr>
    </div>
    <div class="col-xs-6">
        <div id="chart_div_ages" style="width: 900px; height: 500px;"></div>
        <h4>Keskmine ja mediaan vanus</h4>
        <hr>
    </div>
</div>
<div class="row">
    <div class="col-xs-6">
        <h4>Lisa infoga kandidaate parteis</h4>
        <div id="chart_div_additional_data_per_party"></div>
        <hr>
    </div>
</div>
<script type="text/javascript">
    let domain_party = document.getElementById('domain_party')
    let domain_independent = document.getElementById('domain_independent')
    let domain_status
    let belongs_party = document.getElementById('belongs_party')
    let belongs_independent = document.getElementById('belongs_independent')
    let belongs_status

    domain_party.addEventListener('click', function () {
        domain_status=domain_party.value
        drawChartDomains();
    })
    domain_independent.addEventListener('click', function () {
        domain_status=domain_independent.value
        drawChartDomains();
    })

    belongs_independent.addEventListener('click', function () {
        belongs_status = belongs_independent.value
        drawCandidatesPerParty()
    })
    belongs_party.addEventListener('click', function () {
        belongs_status = belongs_party.value
        drawCandidatesPerParty()
    })

</script>


</body>
</html>
