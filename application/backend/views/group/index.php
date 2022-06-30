<?php
$xhtml = '';
if (!empty($this->items)) {
	function showStatus($status)
	{
		if ($status === 'active' || $status == 1) {
			$aClass = "btn-success";
		} elseif ($status === 'inactive' || $status == 0) {
			$aClass = "btn-danger";
		}
		return '<a class="btn '.$aClass.' rounded-circle btn-sm"><i class="fas fa-check"></i></a>';
	}

	foreach ($this->items as $key => $value) {
		$showGroupAcp = showStatus($value['group_acp']);
		$showStatus   = showStatus($value['status']);

		$xhtml .= '<tr>
					<td>' . $value['id'] . '</td>
					<td>' . $value['name'] . '</td>
					<td>' . $showGroupAcp . '</td>
					<td>' . $showStatus . '</td>
					<td>' . $this->arrCountItems[$value['name']] . '</td>
				</tr>';
	}
}
?>
<div class="row">
	<div class="col-12">
		<div class="card card-outline card-info">
			<div class="card-header">
				<h3 class="card-title">List</h3>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table align-middle text-center table-bordered">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Group ACP</th>
								<th>Status</th>
								<th>Members</th>
							</tr>
						</thead>
						<tbody>
							<!-- content -->
							<?= $xhtml ?? '' ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>