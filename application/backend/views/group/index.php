<?php
$token = time();
Session::set('token', $token);
$indexActionLink = URL::createLink($this->params['module'], $this->params['controller'], $this->params['action']);

// $filterLink = $indexActionLink . $this->searchURL . $this->groupAcpURL;
$formActionLink = URL::createLink($this->params['module'], $this->params['controller'], 'form');
$btnAddNew = Helper::createButtonLink($formActionLink, '<i class="fas fa-plus"></i> Add New', 'info');

$inputFilterStatus = '';
$inputSearchKey = '';
$inputGroupACP = '';

if (isset($this->params['filterStatus'])) $inputFilterStatus = Form::input('hidden', 'filterStatus', $this->params['filterStatus']);

if (isset($this->params['search-key'])) $inputSearchKey = Form::input('hidden', 'search-key', $this->params['search-key']);

if (isset($this->params['group_acp'])) $inputGroupACP = Form::input('hidden', 'group_acp', $this->params['group_acp']);

$searchValue = $this->params['search-key'] ?? '';

$groupAcpOptions = [
	'default' => ' - Select Group ACP - ',
	"0" => 'Inactive',
	"1" => 'Active'
];

$groupAcpSelect  = Form::select($groupAcpOptions, 'group_acp', $this->params['group_acp'] ?? '', 'filter-element');

if (!empty(Session::get('notificationElement')) || !empty(Session::get('notification'))) {
	$notification = Helper::showMessege('success', 'Thông báo', [Session::get('notificationElement') ?? 'Thông tin thành viên' => Session::get('notification')]);
	Session::unset('notificationElement');
	Session::unset('notification');
}

$xhtml = '';
if (!empty($this->items)) {
	foreach ($this->items as $key => $value) {
		$id        	  = Helper::highlight($searchValue, $value['id']);
		$name 		  = Helper::highlight($searchValue, $value['name']);
		$pathDelete   = URL::createLink($this->params['module'], $this->params['controller'], 'delete', ['id' => $id]);
		$linkGroupAcp = URL::createLink($this->params['module'], $this->params['controller'], 'changeGroupAcp', ['id' => $id, 'status' => $value['group_acp'], 'token' => $token]);
		$linkStatus   = URL::createLink($this->params['module'], $this->params['controller'], 'changeStatus', ['id' => $id, 'status' => $value['status'], 'token' => $token]);
		$showGroupAcp = Helper::showStatus($value['group_acp'], $linkGroupAcp);
		$showStatus   = Helper::showStatus($value['status'], $linkStatus);

		$editLink = URL::createLink($this->params['module'], $this->params['controller'], 'form', ['id' => $value['id']]);
		$btnEdit 	  = Helper::createButtonLink($editLink, '<i class="fas fa-pen"></i>', 'info', true, true);
		$btnDelete 	  = Helper::createButtonLink($pathDelete, '<i class="fas fa-trash "></i>', 'danger', true, true);

		$xhtml .= '<tr>
					<td>' . $id . '</td>
					<td>' . $name . '</td>
					<td>' . $showGroupAcp . '</td>
					<td class="position-relative">' . $showStatus . '</td>
					<td>
						<p class="mb-0"><i class="far fa-user"></i>' . $value['created_by'] . '</p>
						<p class="mb-0"><i class="far fa-clock"></i>' . $value['created'] . '</p>
					</td>
					<td>
						<p class="mb-0"><i class="far fa-user"></i>' . $value['modified_by'] . '</p>
						<p class="mb-0"><i class="far fa-clock"></i>' . $value['modified'] . '</p>
					</td>
					<td>' . $btnEdit . $btnDelete . '</td>
				</tr>';
	}
}
?>
<div class="row">
	<div class="col-12">
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
						<div style="float: right;"><?= $btnAddNew ?></div>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table align-middle text-center table-bordered">
						<thead>
							<tr>
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
			</div>
		</div>
	</div>
</div>