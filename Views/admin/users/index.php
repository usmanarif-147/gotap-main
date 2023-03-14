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
              <!-- /.card-header -->
              <div class="card-body table-responsive">
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
                        <td style="width: 100px"><?= $user->name ? $user->name : 'N/A' ?></td>
                        <td style="width: 100px"><?= $user->email ?></td>
                        <td style="width: 100px"><?= $user->username ?></td>
                        <td style="width: 100px"><?= $user->tiks ?></td>
                        <td style="width: 100px"><img width="70px" height="70px" src="/<?= $user->photo ? $user->photo : 'assets/default.png' ?>"></td>
                        <td style="width: 100px">
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

                        <th style="width: 100px"><?= $user->card_count ?></th>
                        <td style="width: 100px">
                          <div class="dropdown">
                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                              <i class="bi bi-three-dots"></i>
                            </a>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="/dashboard/users/edit/<?= $user->id ?>">Edit</a>
                              <?php if (!$user->card_count) {
                              ?>
                                <a class="dropdown-item" id="sendNotification_<?= $user->id ?>" onClick="sendNotification(<?= $user->id ?>)" href="javascript:void(0)">
                                  Suspend Notification
                                </a>
                              <?php
                              }
                              ?>
                              <a class="dropdown-item" href="javascript:void(0)" onClick="deleteUser(<?= $user->id ?>)">Delete</a>
                              <?php if ($user->status) {
                              ?>
                                <a id="statusBtn_<?= $user->id ?>" href="javascript:void(0)" onClick="toggleSuspendStatus(<?= $user->id ?>, <?= $user->is_suspended ?>)" class="dropdown-item">
                                  <?= $user->is_suspended ? 'Resume' : 'Suspend' ?>
                                </a>
                              <?php
                              }
                              ?>
                              <a id="authBtn_<?= $user->id ?>" class="dropdown-item" href="javascript:void(0)" onClick="toggleUserStatus(<?= $user->id ?>, <?= $user->status ?>)">
                                <?= $user->status ? 'Deactivate' : 'Activate' ?>
                              </a>
                            </div>
                          </div>
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

  <script>
    const toggleUserStatus = (userId, status) => {

      let textMessage = status == 1 ? 'You want to deactivate user account.' : 'You want to activate user account.';
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
          fetch(`/dashboard/users/changeUserStatus/${userId}`)
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