<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Tab icon -->
        <link rel="shortcut icon" type="image/x-icon" href="images/favicon-16x16.png">

        <!-- jQuery import -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

        <!-- jQuery UI -->
		<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

		<!-- jQuery UI CSS -->
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.css">

		<!-- jQuery Validate -->
		<!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.1/jquery.validate.min.js"> -->

        <!-- Custom CSS -->
        <link rel="stylesheet" type="text/css" href="/css/main.css">

        <!-- API access via JS -->
        <script type="text/javascript" src="/js/uiInteractions.js"></script>

        <!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" 
			integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous">
		</script>

        <!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" 
		integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <title>Reorg Research</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    </head>
    <body>
    	<div class="page-header text-center">
    		<h2>Reorg Research <small>Case Study (Open Payments Data 2015)</small></h2>
    	</div>

    	<!-- Exported Excel file alert -->
    	<div class="row col-md-12">
    		<div class="alert alert-success col-md-offset-4 hidden recordAlerts" role="alert" id="excelFileExported"></div>
    	</div>

    	<!-- Error message -->
    	@if (isset($noRecordsFoundMessage))
	    	<div class="row col-md-12">
	    		<div class="alert alert-danger col-md-offset-4 recordAlerts" role="alert" id="noRecordsFoundAlert">{{ $noRecordsFoundMessage }}</div>
	    	</div>
	    @endif

    	<div class="text-center row col-md-12">
    		<!-- <button type="submit" class="btn btn-default" id="updateDatabaseButton">Update Database</button> -->
    		<form method="POST" action="/download" name="buildFileForm" id="buildFileForm">
    			{{ csrf_field() }}
	    		<button type="submit" class="btn btn-default" id="importDataButton">Import Data</button>
	    		<button type="submit" class="btn btn-primary" id="searchDataButton">Search</button>
    			<a class="btn btn-success hidden" id="exportToExcelButton">Export Excel</a>
    			<input type="hidden" name="matches" value="" id="matchesInput">
    		</form>

    		<!-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#importOptionsModal">Open Modal</button> -->
    	</div>

    	<!-- Search input box -->
    	<div class="row text-center col-md-12">
    		<input type="search" name="searchBox" id="searchBox" class="form form-control col-md-offset-4" placeholder="Search..." />
    		<label class="hidden" id="searchBoxValidationMessage">Search box cannot be empty</label>
    	</div>

    	<!-- Found records notification -->
    	<div class="row col-md-12">
    		<div class="alert alert-warning col-md-offset-4 hidden recordAlerts text-center" role="alert" id="foundRecordsAlert"></div>
    	</div>

    	<!-- Indicate current number of records -->
    	<div class="text-center row col-md-12" id="recordCountDiv">
    		<blockquote>
    			<p>Records in DB: <span id="dbRecordCount">{{ $recordCount }}</span></p>
    		</blockquote>
    	</div>

    	<!-- Download excel file link -->
    	<div class="row text-center">
    		<a href="/" id="downloadFileLink" class="hidden">Download Excel File</a>
    	</div>
    </body>
</html>