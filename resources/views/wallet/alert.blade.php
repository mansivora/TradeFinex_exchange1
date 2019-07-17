    @if(count($errors) > 0) 
           <div class="alert alert-danger">
<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        @foreach($errors->all() as $er)
        {{$er}}<br/>
        @endforeach
        </div>
        @endif
         @if(Session::has('error'))
    <div class="alert alert-danger">
<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{ Session('error') }}</div>
    @endif

     @if(Session::has('success'))
    <div class="alert alert-success">
<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{ Session('success') }}</div>
    @endif