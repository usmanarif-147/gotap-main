  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Platforms</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Platforms</li>
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
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Title</th>
                      <th>Category</th>
                      <th>Type</th>
                      <th>Status</th>
                      <th>Icon</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $i = 0;
                    foreach ($platforms as $platform) {
                      $i++;

                    ?>
                      <tr id="platform_<?= $platform->id ?>">
                        <td><?= $i ?></td>
                        <td><?= $platform->title ?></td>
                        <td><?= $platform->category ?></td>
                        <td><?= $platform->pro ? 'Pro' : 'Free' ?></td>
                        <td><?= $platform->status ? 'Active' : 'Inactive' ?></td>
                        <td><img width="48px" height="48px" src="../<?= $platform->icon ?>"></td>
                        <td>
                          <a href="/dashboard/platforms/edit/<?= $platform->id ?>">edit</a>
                          <a class="ml-2" href="#" onClick="deletePlatform(<?= $platform->id ?>)">delete</a>
                        </td>
                      </tr>

                    <?php } ?>

                  </tbody>
                  <tfoot>
                    <tr>
                      <th>#</th>
                      <th>Title</th>
                      <th>Category</th>
                      <th>Type</th>
                      <th>Status</th>
                      <th>Icon</th>
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

  <script>
    const deletePlatform = (id) => {
      if (confirm('Do you really want to delete? This will also be removed from users profile  who has added this platform')) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText); //print

            let res = JSON.parse(this.responseText);
            if (res.status == 200) {
              document.querySelector(`#platform_${id}`).remove();
            } else {
              alert('Ooops platform could not be deleted | query error');
            }
          }
        };
        xhttp.open("GET", `/dashboard/platforms/delete/${id}`, true);
        xhttp.send();
      }
    }
  </script>