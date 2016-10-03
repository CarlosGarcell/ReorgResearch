$(document).ready(function() {

	// Whenever the tab is reloaded, we want to clear the sessionStorage to make sure we're not keeping
	// old excel data in it and risk mixing up search results.
	sessionStorage.clear();

	var searchBoxInput = $('#searchBox');
	var searchDataButtonElement = $('#searchDataButton');
	var exportToExcelButton = $('#exportToExcelButton');

	searchBoxInput.focus();

	searchBoxInput.autocomplete({
		minLength: 2,
		source: function(request, response) {
			$.ajax({
				url: '/autocomplete',
				dataType: 'json',
				data: {
					keyword: getTypeaheadKeyword(request.term)
				},				
				success: function(data) {
					response(data)
				}
			});
		},
		focus: function(event, ui) {
			if (searchBoxInput.val() !== '') {
				event.preventDefault()
			}
		},
		select: function(event, ui) {
			var inputValue = event.target.value;
			var inputValueArray = inputValue.split(' ');

			if (inputValueArray.length > 1) {
				event.preventDefault()
				inputValueArray[inputValueArray.length-1] = ui.item.value
				event.target.value = inputValueArray.join(' ')
			}
		}
	})

	// Handle export to excel interaction
	exportToExcelButton.click(function() {
		exportData($(this));
	});

	var importDataButtonElement = $('#importDataButton');

	// Handle impot button interaction
	importDataButtonElement.click(function() {
		importData($(this), searchDataButtonElement, searchBoxInput);
	});

	// Handle search submit with enter key
	searchBoxInput.keypress(function(event) {
		if (event.which === 13 && searchRequestIsValid(searchBoxInput)) {
			searchData(searchDataButtonElement, exportToExcelButton)
		}
	})

	// Handle search button interaction
	searchDataButtonElement.click(function(event, ui) {
		if (searchRequestIsValid(searchBoxInput)) {
			searchData(searchDataButtonElement, exportToExcelButton);
		}
	});
});

function searchRequestIsValid(searchBoxInput) {
	if(!(searchBoxInput.val() === '') && searchBoxInput.val().replace(/\s/g, '').length > 0) {
		$('#searchBoxValidationMessage').addClass('hidden');
		searchBoxInput.removeClass('invalidInput');
		return true;
	}
	
	searchBoxInput.val('');
	$('#searchBoxValidationMessage').removeClass('hidden');
	searchBoxInput.addClass('invalidInput');
	return false;
}

/**
 * [getTypeaheadKeyword description]
 * @param  {[type]} string [description]
 * @return {[type]}        [description]
 */
function getTypeaheadKeyword(string) {
	var stringArray = string.split(' ');
	var keywordToSearchIndex = stringArray.length-1;
	return stringArray[keywordToSearchIndex];
}

/**
 * [importData description]
 * @param  {[type]} importDataButtonElement   [description]
 * @param  {[type]} fetchRecordsButtonElement [description]
 * @return {[type]}                           [description]
 */
function importData(importDataButtonElement, searchDataButtonElement, searchBoxInput) {
	// Disable 'Import Data' button while data is being fetched from API
	importDataButtonElement.text('Importing Data...');
	importDataButtonElement.attr('disabled', true);

	// Disable search button
	searchDataButtonElement.attr('disabled', true);

	// Disable search box while data is being fetched from the API.
	searchBoxInput.attr('disabled', true);

	$.ajax({
		type: 'POST',
		url: '/import',
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		timeout: 540000,
		error: function() {

		}
	}).done(function(data) {
		var jsonData = JSON.parse(data);

		if (jsonData.savedRecordsCount === 0) {
			alert('No records were downloaded from the server')
		}

		// Enable Import data button
		importDataButtonElement.text('Import Data');
		importDataButtonElement.removeAttr('disabled');

		// Enable search button
		searchDataButtonElement.removeAttr('disabled');

		// Enable search input.
		searchBoxInput.removeAttr('disabled');
		searchBoxInput.focus();

		// Assign current record count to screen counter.
		$('#dbRecordCount').text(jsonData.dbRecordCount);
	});
}

/**
 * [searchData description]
 * @param  {[type]} searchDataButtonElement [description]
 * @return {[type]}                         [description]
 */
function searchData(searchDataButtonElement, exportToExcelButton, searchBoxInput) {
	searchDataButtonElement.text('Searching...');
	searchDataButtonElement.attr('disabled', true);

	// Clear sessionStorage before searching for something else.
	if (sessionStorage.getItem('excelData')) sessionStorage.removeItem('excelData');

	$.ajax({
		type: 'GET',
		url: '/search',
		data: {
			searchData : $('#searchBox').val()
		},
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	}).done(function(data) {
		var jsonData = JSON.parse(data);
		
		// Reset search data button.
		searchDataButtonElement.text('Search');
		searchDataButtonElement.removeAttr('disabled');

		// Enable "Export Excel" button.
		if (jsonData.total_found > 0)  {
			exportToExcelButton.removeClass('hidden')
		} else {
			alert('No records were found!')
			exportToExcelButton.addClass('hidden');
		}

		sessionStorage.setItem('excelData', JSON.stringify(jsonData.matches));
	})
}

/**
 * [exportData description]
 * @param  {[type]} data [description]
 * @return {[type]}      [description]
 */
function exportData(exportToExcelButton) {
	exportToExcelButton.attr('disabled', true);
	exportToExcelButton.text('Exporting...');

	$.ajax({
		type: 'POST',
		url: `/buildfile`,
		data: {
			matches: sessionStorage.getItem('excelData')
		},
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		complete: function(response) {
			exportToExcelButton.removeAttr('disabled');
			exportToExcelButton.text('Export Excel');
		}
	})
}