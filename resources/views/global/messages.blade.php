@if(Session::has('success'))
<div class="alert alert-success">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Успешно!</strong> {{Session::get('success')}}
</div>
@endif
@if(Session::has('info'))
<div class="alert alert-info">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  {{Session::get('info')}}
</div>
@endif
@if(Session::has('warning'))
<div class="alert alert-warning">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Внимание!</strong> {{Session::get('warning')}}
</div>
@endif
@if(Session::has('danger'))
<div class="alert alert-danger">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Ошибка!</strong> {{Session::get('danger')}}
</div>
@endif