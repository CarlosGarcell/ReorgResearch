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
			if(request.term.replace(/' '/, '').length > 0) {
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
			}
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

/**
 * [searchRequestIsValid 
 * Checks whether the current request made by the user is a valid one]
 * 
 * @param  {[HTML Element]} searchBoxInput 
 * @return {[boolean]}             [Indicates whether the request was valid or not]
 */
function searchRequestIsValid(searchBoxInput) {
	if(!(searchBoxInput.val() === '') && searchBoxInput.val().replace(/\s/g, '').length > 0) {
		$('#searchBoxValidationMessage').addClass('hidden');
		searchBoxInput.removeClass('invalidInput');
		return true;
	}
	
	// Request is invalid
	searchBoxInput.val('');
	$('#searchBoxValidationMessage').removeClass('hidden');
	searchBoxInput.addClass('invalidInput');
	return false;
}

/**
 * [getTypeaheadKeyword 
 * Extracts the keyword from which we should offer suggestions to the user]
 * 
 * @param  {[string]} string [The current value of the search input]
 * @return {[string]}        [The string to be searched in the DB for suggestions]
 * 
 */
function getTypeaheadKeyword(string) {
	var stringArray = string.split(' ');
	var keywordToSearchIndex = stringArray.length-1;
	return stringArray[keywordToSearchIndex];
}

/**
 * [importData 
 * Sends an AJAX request to the backend to pull records from the API
 * The max amount of records to be pulled has been set to 3000 for permonce reasons]
 * 
 * @param  {[HTML Element]} importDataButtonElement 
 * @param  {[HTML Element]} searchDataButtonElement 
 * @param  {[HTML Element]} searchBoxInput          
 * 
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
		error: function(response) {
			alert(error)
		}
	}).done(function(data) {
		var jsonData = JSON.parse(data);

		if (jsonData.savedRecordsCount === 0) alert('No records were downloaded from the server');

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
 * [searchData 
 * Sends an AJAX request to the backend requesting any matching records
 * Once the request recieves a response, it will enable the 'Export Excel' button
 * or alert the user that no records were found in the DB based on the current criteria]
 * 
 * @param  {[HTML Element]} searchDataButtonElement
 * @param  {[HTML Element]} exportToExcelButton
 * @param  {[HTML Element]} searchBoxInput
 * 
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

		var totalRecords = jsonData.total;
		var totalFoundRecords = jsonData.total_found;

		// Enable "Export Excel" button.
		if (totalFoundRecords > 0)  {
			var foundRecordsAlertElement = $('#foundRecordsAlert');
			if(totalRecords == 1000) {
				var foundRecordsAlertNotification = `Total Found: ${jsonData['total_found']} - Records to Export: ${jsonData['total']}. 
				(We only export the top 1000 records due to performance reasons)`;
			} else if(totalRecords < 1000) {
				var foundRecordsAlertNotification = `Total Found: ${jsonData['total_found']} - Records to Export: ${jsonData['total']}`
			}

			exportToExcelButton.removeClass('hidden');
			foundRecordsAlertElement.removeClass('hidden');
			foundRecordsAlertElement.text(foundRecordsAlertNotification);
		} else {
			alert('No records were found!')
			exportToExcelButton.addClass('hidden');
		}

		sessionStorage.setItem('excelData', JSON.stringify(jsonData.matches));
	})
}

/**
 * [exportData Sends an AJAX request to the backend to build and export the Excel file]
 * 
 * @param  {[HTML element]} exportToExcelButton
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
			var excelFileData = JSON.parse(response.responseText);

			// Hide foundRecordAlertElement as it is no longer needed
			$('#foundRecordsAlert').addClass('hidden');

			exportToExcelButton.removeAttr('disabled');
			exportToExcelButton.text('Export Excel');

			var excelNotificationMessage = `File ${excelFileData['fileName']} was exported and stored in ${excelFileData['storagePath']}`;

			var excelFileExportedDiv = $('#excelFileExported');

			var styleAttribute = excelFileExportedDiv.attr('style');

			// On slideUp, jQuery adds 'style=display: none;' to the element, thus rendering it invisible for the rest
			// of the current execution. This is a simple workaraound that to allow the alert to be shown repeatedly.
			if (!styleAttribute) {
				excelFileExportedDiv.removeClass('hidden');
			} else {
				excelFileExportedDiv.removeAttr('style');
			}

			excelFileExportedDiv.text(excelNotificationMessage);

			excelFileExportedDiv.delay(5000).slideUp();
		}
	})
}