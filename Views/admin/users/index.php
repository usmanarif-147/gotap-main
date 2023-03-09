  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Users</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Users</li>
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
              <div class="card-header">
                <h3 class="card-title">Users List</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Username</th>
                      <th>Tiks</th>
                      <th>Photo</th>
                      <th>Status</th>
                      <th>Active Products</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $i = 0;
                    foreach ($users as $user) {
                      $i++;
                    ?>
                      <tr id="user_<?= $user->id ?>">
                        <td><?= $user->name ?></td>
                        <td><?= $user->email ?></td>
                        <td><?= $user->username ?></td>
                        <td><?= $user->tiks ?></td>
                        <td><img width="70px" height="70px" src="/<?= $user->photo ? $user->photo : 'assets/default.png' ?>"></td>
                        <td>
                          <?php if ($user->status == 1 && $user->is_suspended == 0) {
                          ?>
                            <span class="badge badge-success">
                              Actice
                            </span>
                          <?php
                          } elseif ($user->status == 1 && $user->is_suspended == 1) {
                          ?>
                            <span class="badge badge-warning">
                              Suspended
                            </span>
                          <?php
                          } elseif ($user->status == 0) {
                          ?>
                            <span class="badge badge-danger">
                              Inactive
                            </span>
                          <?php
                          }
                          ?>
                        </td>

                        <th><?= $user->card_count ?></th>
                        <td>
                          <a class="btn btn-sm btn-info" href="/dashboard/users/edit/<?= $user->id ?>">edit</a>
                          <?php if (!$user->card_count) {
                          ?>
                            <button id="sendNotification_<?= $user->id ?>" onClick="sendNotification(<?= $user->id ?>)" class="btn btn-sm btn-warning">
                              Suspend Notification
                            </button>
                          <?php
                          }
                          ?>
                          <a class="btn btn-sm btn-danger ml-2" href="#" onClick="deleteUser(<?= $user->id ?>)">delete</a>
                          <button id="statusBtn_<?= $user->id ?>" onClick="toggleSuspendStatus(<?= $user->id ?>, <?= $user->is_suspended ?>)" class="btn btn-sm <?= $user->is_suspended ? 'btn-secondary' : 'btn-warning' ?>">
                            <?= $user->is_suspended ? 'Resume' : 'Suspend' ?>
                          </button>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Username</th>
                      <th>Tiks</th>
                      <th>Photo</th>
                      <th>Status</th>
                      <th>Active Products</th>
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
    const deleteUser = (id) => {
      if (confirm('Do you really want to delete?')) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText); //print

            let res = JSON.parse(this.responseText);
            if (res.status == 200) {
              document.querySelector(`#user_${id}`).remove();
            } else {
              alert('Ooops user could not be deleted | query error');
            }
          }
        };
        xhttp.open("GET", `/dashboard/users/delete/${id}`, true);
        xhttp.send();
      }
    }
  </script>
  <script>
    const toggleSuspendStatus = (userId, status) => {

      let textMessage = status == 1 ? 'You want to resume user account' : 'You want to suspend user account';
      // Display the SweetAlert2 confirmation dialog
      Swal.fire({
        title: 'Are you sure?',
        text: textMessage,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes!'
      }).then((result) => {
        // If the user clicked "Yes"
        if (result.isConfirmed) {
          // Make the AJAX call using jQuery
          fetch(`/dashboard/users/changeSuspendStatus/${userId}`)
            .then((response) => response.json())
            .then((data) => {
              const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                  toast.addEventListener('mouseenter', Swal.stopTimer)
                  toast.addEventListener('mouseleave', Swal.resumeTimer)
                },
                didClose: () => {
                  location.reload();
                }
              })
              Toast.fire({
                icon: 'success',
                title: data.message
              })
            });
        }
      });

    }

    const sendNotification = (userId) => {

      Swal.fire({
        title: 'Are you sure?',
        text: 'You want to send suspend notification?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes!'
      }).then((result) => {
        // If the user clicked "Yes"
        if (result.isConfirmed) {
          // Make the AJAX call using jQuery
          fetch(`/dashboard/users/suspendNotification/${userId}`)
            .then((response) => response.json())
            .then((data) => {
              const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                  toast.addEventListener('mouseenter', Swal.stopTimer)
                  toast.addEventListener('mouseleave', Swal.resumeTimer)
                },
                didClose: () => {
                  location.reload();
                }
              })
              Toast.fire({
                icon: 'success',
                title: data.message
              })
            });
        }
      });

    }
  </script>