  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Categories</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Categories</li>
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
                <h3 class="card-title">Categories</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-striped">
                  <thead>
                  <tr>
					<th>#</th>
                    <th>Title (en)</th>
                    <th>Title  (sv)</th>
                    <th>Status</th>
					<th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
				  <?php $i=0; foreach($categories as $category) { 
					$i++;
					
				  ?>
                  <tr id="category<?=$category->id ?>">
					<td><?=$i?></td>
                    <td><?=$category->name ?></td>
					<td><?=$category->name_sv ?></td>
                    <td><?=$category->status ?'Active':'Inactive'?></td>
					<td>
            <a href="/dashboard/categories/edit/<?=$category->id ?>">edit</a>
          </td>
                  </tr>
                  
				  <?php } ?>
				  
                  </tbody>
                  <tfoot>
                  <tr>
					<th>#</th>
                    <th>Title (en)</th>
                    <th>Title  (sv)</th>
                    <th>Status</th>
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

  const deletePlatform=(id)=>{
      if(confirm('Do you really want to delete? This will also be removed from users profile  who has added this platform')){
          var xhttp = new XMLHttpRequest();
          xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              console.log(this.responseText);//print
              
              let res=JSON.parse(this.responseText);
              if(res.status==200){
                  document.querySelector(`#platform_${id}`).remove();
              }else{
                alert('Ooops platform could not be deleted | query error');
              }
            }
          };
          xhttp.open("GET", `/dashboard/platforms/delete/${id}`, true);
          xhttp.send();
      }
    }

  </script>