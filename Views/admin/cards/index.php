  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Cards</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Cards</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <?php
            if (isset($_SESSION['success'])) { ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success']; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <?php
              // Clear the session message
              unset($_SESSION['success']);
            }
            ?>
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"></h3>
                <a class="my-auto btn btn-info btn-sm mb-3 text-center float-right" href="/dashboard/cards/export">
                  <i class="mdi mdi-download me-2 "></i>
                  Download Inactive Cards
                </a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <!--<th>#</th>-->
                      <th>Uuid</th>
                      <th>Description</th>
                      <th>Status</th>
                      <th>Assigned Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $i = 0;
                    foreach ($cards as $card) {
                      $i++;
                    ?>
                      <tr id="card<?= $card->id ?>">
                        <!--<td><?= $i ?></td>-->
                        <td>
                          <div class="row">
                            <div class="col-md-10">
                              <?= $card->uuid ?>
                            </div>
                            <div class="col-md-2">
                              <a href="javascript:void(0)" onclick="copy(<?= $card->id ?>)">
                                <i class="fa fa-clipboard" data-toggle="tooltip" data-placement="top" title="Copy Link" aria-hidden="true"></i>
                              </a>

                            </div>
                          </div>

                        </td>
                        <td><?= $card->description ?></td>
                        <td>
                          <span class="badge <?= $card->status ? 'badge-success' : 'badge-danger' ?>">
                            <?= $card->status ? 'Active' : 'Inactive' ?>
                          </span>
                        </td>
                        <td>
                          <span class="badge <?= $card->is_assigned ? 'badge-secondary' : 'badge-warning' ?>">
                            <?= $card->is_assigned ? 'Assigned' : 'Not Assigned' ?>
                          </span>
                        </td>
                        <td>
                          <input id="card-<?= $card->id ?>" type="hidden" value="<?= $card->uuid ?>">
                          <a class="btn btn-sm btn-primary" href="/dashboard/cards/edit/<?= $card->id ?>">
                            Edit
                          </a>
                          <!-- <a class="btn btn-sm btn-dark" href="javascript:void(0)" onclick="copy(<?= $card->id ?>)">
                            Copy
                          </a> -->
                          <button id="assignBtn_<?= $card->id ?>" onClick="toggleAssignStatus(<?= $card->id ?>)" data-id="<?= $card->id ?>" class="assignBtn btn btn-sm <?= $card->is_assigned ? 'btn-secondary' : 'btn-warning' ?>">
                            <?= $card->is_assigned ? 'Assigned' : 'Not Assigned' ?>
                          </button>
                        </td>
                      </tr>

                    <?php } ?>

                  </tbody>
                  <tfoot>
                    <tr>
                      <!--<th>#</th>-->
                      <th>Uuid</th>
                      <th>Description</th>
                      <th>Status</th>
                      <th>Assigned Status</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <script src="/assets/admin/plugins/jquery/jquery.min.js"></script>
  <script>
    const toggleAssignStatus = (cardId) => {
      const btn = document.querySelector(`#assignBtn_${cardId}`);

      fetch(`/dashboard/cards/change/${cardId}`)
        .then((response) => response.json())
        .then((data) => {
          // console.log(data)
          const isAssigned = btn.innerText == 'Assigned';
          btn.innerText = isAssigned ? 'Not Assigned' : 'Assigned';
          btn.classList.remove(isAssigned ? 'btn-secondary' : 'btn-warning');
          btn.classList.add(isAssigned ? 'btn-warning' : 'btn-secondary');
          location.reload();
        });
    }

    function copy(id) {
      let url = window.location.origin + '/card_id' + '/' + $('#card-' + id).val();

      const textArea = document.createElement("textarea");
      textArea.value = url;
      document.body.appendChild(textArea);

      // Select and copy the text
      textArea.select();
      document.execCommand("copy");

      // Remove the text area
      document.body.removeChild(textArea);

      alert("copied");
    }

    function changeStatus(id) {

    }
  </script>