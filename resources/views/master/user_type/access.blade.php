@extends('layout.template_dash')
@section('content')
  <main id="main-container">
    <div class="bg-body-light">
      <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
          <h1 class="flex-sm-fill h3 my-2">
            {{ $title }} 
            <small class="d-block d-sm-inline-block mt-2 mt-sm-0 font-size-base font-w400 text-muted">{{ $subtitle }}</small>
          </h1>         
        </div>
      </div>
    </div>
    <div class="content">       
    <?php if (session()->has('msg')) { ?>
      {!! session()->get('msg') !!}                          
    <?php session()->forget('msg'); } ?>            
    
      <div class="block">
        <div class="block-header block-header-default">
          <h3 class="block-title">
            <a class="btn btn-warning btn-sm float-right" href="{{ route('user_type.index') }}"> <i class="fa fa-chevron-left"></i> Back</a>
          </h3>
        </div>
        <div class="block-content block-content-full">                  

          <?php $row = $user_type; ?>
          <form class="mb-5" action="{{ route('user_type.updateAccess',$row->id) }}" method="POST">         
            <input type="hidden" name="_token" value="{{ csrf_token() }}">                    
            <div class="form-group row">
              <label class="col-sm-2 col-form-label" >Nama</label>
              <div class="col-sm-10">               
                <input readonly type="text" class="form-control" name="name" value="<?=($row)?$row->name:'';?>" placeholder="Nama">
              </div>
            </div>            
            <div class="form-group row">
              <div class="table-responsive">
                <table id="example_tombol" class="table" style="width:100%">                  
                  <thead>
                    <tr>
                      <th width="5%">No</th>
                      <th>Menu (Check All <input type="checkbox" id="select-all">)</th>
                      <th class='text-center' width="15%">Can Insert</th>                        
                      <th class='text-center' width="15%">Can View</th>                                                    
                      <th class='text-center' width="15%">Can Edit</th>                                                    
                      <th class='text-center' width="15%">Can Delete</th>                                                    
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  $no=1;
                  $sql = DB::table("menu")->get();                  
                  foreach ($sql as $isi) {
                    $jum = $sql->count();
                    $cek = DB::table("user_access")->where("menu_id",$isi->id)->where("user_type_id",$row->id);
                    if($cek->count() > 0){
                        $c = $cek->first();
                      if($c->can_view == 1) $view = "checked";
                        else $view = "";                        
                      if($c->can_edit == 1) $edit = "checked";
                        else $edit = "";
                      if($c->can_insert == 1) $insert = "checked";
                        else $insert = "";
                      if($c->can_delete == 1) $delete = "checked";
                        else $delete = "";
                    }else{
                      $view = "";
                      $edit = "";
                      $insert = "";
                      $delete = "";
                    }                       
                    echo "
                    <tr>
                      <td>$no</td>
                      <td>$isi->name</td>
                      <th class='text-center'>
                        <input type='hidden' name='id_menu_$no' value='$isi->id'>
                        <input type='hidden' name='jml' value='$jum'>
                        <input class='data-check' type='checkbox' $insert name='insert_$no' value=1>
                      </th>
                      <th class='text-center'><input type='checkbox' $view name='view_$no' value=1></th>
                      <th class='text-center'><input type='checkbox' $edit name='edit_$no' value=1></th>
                      <th class='text-center'><input type='checkbox' $delete name='delete_$no' value=1></th>
                    </tr>
                    ";
                    $no++;
                  }
                  ?>
                  </tbody>
                </table>
              </div>  
            </div>
            <div class="form-group row">
              <div class="col-sm-10 ml-auto">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                <button type="reset" class="btn btn-default">Reset</button>
              </div>
            </div>
          </form>

        </div>      
      </div>
    </div>
  </main>

<script type="text/javascript">
document.getElementById('select-all').onclick = function() {
  var checkboxes = document.querySelectorAll('input[type="checkbox"]');
  for (var checkbox of checkboxes) {
    checkbox.checked = this.checked;
  }
}
</script>
@endsection


