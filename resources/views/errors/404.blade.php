{{--<!DOCTYPE html>--}}
{{--<html>--}}
{{--<head>--}}
{{--<title></title>--}}
{{--</head>--}}
{{--<body>--}}
{{--<center><img src="{{URL::asset('front/assets/imgs')}}/error-404.png" /></center>--}}
{{--</body>--}}
{{--</html>--}}

@extends('front.layout.front')
@section('content')
<div class="clearfix"></div>
<div class="main-flex">
	<div class="main-content inner_content">
		<div class="container-fluid">
			<div class="row">

				<div class="col-md-12">
					<div class="panel panel-default panel-heading-space text-center error-main">
						<div class="oops-image">
							<img src="{{URL::asset('front')}}/assets/imgs/oops-icon.png" alt="oops" />
						</div>
						<h1>Oops!</h1>
						<p>Something went wrong and we couldn't process your request.</p>
						<p>Please go back to the previuos page and try again.</p>
						<button type="button" class="btn yellow-btn min-width-btn go-back" onclick="history.back();">Go Back</button>
					</div>
				</div>

			</div>

		</div>
	</div>
	<div class="clearfix"></div>
</div>
@endsection

