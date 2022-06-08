<?php
$xhtml = '';
$token = Helper::randomString(10);
Session::set('token', $token);
$indexActionLink = URL::createLink($this->params['module'], $this->params['controller'], $this->params['action']);
$formActionLink = URL::createLink($this->params['module'], $this->params['controller'], 'form');
$btnAddNew = Helper::createButtonLink($formActionLink, '<i class="fas fa-plus"></i> Add New', 'info');

if(!empty(Session::get('notificationElement')) || !empty(Session::get('notification'))){
	$notification = Form::showMessege('success', 'Thông báo', [Session::get('notificationElement') ?? '' => Session::get('notification')]);
	Session::unset('notificationElement');
	Session::unset('notification');
}

if (!empty($this->items)) {
	foreach ($this->items as $key => $value) {
		$id        	  = Helper::highlight($this->params['search-key'] ?? '', $value['id']);
		$name 		  = Helper::highlight($this->params['search-key'] ?? '', $value['name']);
		$pathDelete   = URL::createLink($this->params['module'], $this->params['controller'], 'delete', ['id' => $id]);
		$linkGroupAcp = URL::createLink($this->params['module'], $this->params['controller'], 'changeGroupAcp', ['id' => $id, 'status' => $value['group_acp'], 'token' => $token]);
		$linkStatus   = URL::createLink($this->params['module'], $this->params['controller'], 'changeStatus', ['id' => $id, 'status' => $value['status'], 'token' => $token]);
		$showGroupAcp = Helper::showStatus($value['group_acp'], $linkGroupAcp);
		$showStatus   = Helper::showStatus($value['status'], $linkStatus);

		$editLink = URL::createLink($this->params['module'], $this->params['controller'], 'form', ['id' => $value['id']]);
		$btnEdit 	  = Helper::createButtonLink($editLink, '<i class="fas fa-pen"></i>', 'info', true, true);
		$btnDelete 	  = Helper::createButtonLink($pathDelete, '<i class="fas fa-trash "></i>', 'danger', true, true);

		$xhtml .= '<tr>
					<td><input type="checkbox"></td>
					<td>' . $id . '</td>
					<td>' . $name . '</td>
					<td>' . $showGroupAcp . '</td>
					<td>' . $showStatus . '</td>
					<td>
						<p class="mb-0"><i class="far fa-user"></i>' . $value['created_by'] . '</p>
						<p class="mb-0"><i class="far fa-clock"></i>' . $value['created'] . '</p>
					</td>
					<td>
						<p class="mb-0"><i class="far fa-user"></i>' . $value['modified_by'] . '</p>
						<p class="mb-0"><i class="far fa-clock"></i>' . $value['modified'] . '</p>
					</td>
					<td>
						' . $btnEdit . $btnDelete . '
					</td>
				</tr>';
	}
}
?>
<div class="row">
	<div class="col-12">
		<!-- Search & Filter -->
		<div class="card card-outline card-info">
			<div class="card-header">
				<h3 class="card-title">Search & Filter</h3>

				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse">
						<i class="fas fa-minus"></i>
					</button>
				</div>
			</div>
			<div class="card-body">
				<div class="container-fluid">
					<div class="row justify-content-between align-items-center">
						<div class="area-filter-status mb-2">
							<?= Form::areaFilterStatus($indexActionLink, $this->arrCountItems, $this->params['filterStatus'] ?? 'all') ?>
						</div>
						<div class="area-search mb-2">
							<form action="" method="GET">
								<?php echo Form::input('hidden', 'module', 'backend') ?>
								<?php echo Form::input('hidden', 'controller', 'group') ?>
								<?php echo Form::input('hidden', 'action', 'index') ?>
								<div class="input-group">
									<input type="text" class="form-control" name="search-key" value="<?= $this->params['search-key'] ?? '' ?>">
									<span class="input-group-append">
										<button type="submit" class="btn btn-info">Search</button>
										<a href="<?= $indexActionLink ?>" class="btn btn-danger">Clear</a>
									</span>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- /.card-body -->
		</div>
		<!-- List -->
		<div class="card card-outline card-info">
			<div class="card-header">
				<h3 class="card-title">List</h3>
				<div class="card-tools">
					<a href="<?= $indexActionLink ?>" class="btn btn-tool" data-card-widget="refresh">
						<i class="fas fa-sync-alt"></i>
					</a>
					<button type="button" class="btn btn-tool" data-card-widget="collapse">
						<i class="fas fa-minus"></i>
					</button>
				</div>
			</div>
			<div class="card-body">
				<?= $notification ?? '' ?>
				<div class="container-fluid">
					<div class="row align-items-center justify-content-between mb-2">
						<div>
							<div class="input-group">
								<select class="form-control custom-select">
									<option>Bulk Action</option>
									<option>Delete</option>
									<option>Active</option>
									<option>Inactive</option>
								</select>
								<span class="input-group-append">
									<button type="button" class="btn btn-info">Apply</button>
								</span>
							</div>
						</div>
						<div><?= $btnAddNew ?></div>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table align-middle text-center table-bordered">
						<thead>
							<tr>
								<th><input type="checkbox"></th>
								<th>ID</th>
								<th>Name</th>
								<th>Group ACP</th>
								<th>Status</th>
								<th>Created</th>
								<th>Modified</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<!-- content -->
							<?= $xhtml; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="card-footer clearfix">
				<ul class="pagination m-0 float-right">
					<li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-angle-double-left"></i></a></li>
					<li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-angle-left"></i></a></li>
					<li class="page-item active"><a class="page-link" href="#">1</a></li>
					<li class="page-item"><a class="page-link" href="#">2</a></li>
					<li class="page-item"><a class="page-link" href="#">3</a></li>
					<li class="page-item"><a class="page-link" href="#"><i class="fas fa-angle-right"></i></a></li>
					<li class="page-item"><a class="page-link" href="#"><i class="fas fa-angle-double-right"></i></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>