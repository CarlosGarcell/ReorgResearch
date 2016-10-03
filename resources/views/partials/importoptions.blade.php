<div class="modal fade" id="importOptionsModal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4>Choose how many records to import...</h4>
			</div>
			<div class="modal-body">
				<p>
					WARNING: Only a max of 50,000 records can be imported per operation.
					Fetching said amount could take longer than 3 minutes and has a higher risk
					of failing due to connection timeout. We suggest you fetch 5,000 records at most.
				</p>
				<form method="POST" id="numberOfRecordToFetchForm">
					<label>Number of records to fetch</label>
					<input type="number" name="numberOfRecordToFetch" id="numberOfRecordsToFetchInput" class="form-control" min="1" max="50000" required="required">
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary" id="fetchRecordsButton" data-dismiss="modal">Fetch Records</button>
			</div>
		</div>
	</div>
</div>