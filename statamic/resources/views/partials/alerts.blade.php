<div class="flashdance">
	@if (!$is_trial && !$is_correct_domain)
	<div class="alert alert-danger alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<p>This license belongs to another domain. Visit <a href="https://account.statamic.com/licenses" target="_blank">your account</a> to change it.</p>
	</div>
	@endif

	@if ($is_unlicensed)
	<div class="alert alert-danger alert-dismissible" role="alert">
		<p>You are on a public domain without a license. Please <a href="{{ route('settings', 'system') }}">add your license key</a>.</p>
	</div>
	@endif

	@if (session('success'))
	<div class="alert alert-success alert-dismissible" role="alert">
	  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  {{ session('success') }}
	</div>
	@endif

	@if ($errors->count())
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			@foreach($errors->all() as $error){{ $error }}@endforeach
		</div>
	@endif

    <div class="alert alert-success alert-dismissible" role="alert" v-if="flashSuccess" v-cloak>
	  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  @{{ flashSuccess }}
	</div>

    <div class="alert alert-danger alert-dismissible" role="alert" v-if="flashError" v-cloak>
	  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  @{{ flashError }}
	</div>

</div>
