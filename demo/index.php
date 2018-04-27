<!DOCTYPE html>
<html lang="en">
<head>
	<script src="https://unpkg.com/vue"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue-resource@1.3.4"></script>
	<link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css' rel='stylesheet'>
	<style>
		.container {
			max-width: 920px;
			margin-top: 40px;
		}
	</style>
</head>
<body>
<div id="app">
	<div class='container'>
		<h3>Filter</h3>
		<input type='text' v-model='query' class='form-control' placeholder='Type query here' v-bind:class="{'is-invalid': error}">
		<p class='text-danger'>{{ error }}&nbsp;</p>
		<pre>{{ result }}</pre>
	</div>
</div>

<script>
var app = new Vue({
	el: '#app',
	watch: {
		query: function (value, old) {

			this.update(value);

		}
	},
	methods: {
		update(value) {
			this.$http.get('/server.php', {params: {q: value}})
			.then(response => {
				this.error = "";
		  		this.result = response.body.query;
			}, response => {
				this.error = response.body.message;
		  		this.result = '';
			});
		}
	},
	mounted() {
		this.update(this.query);
	},
	data: {
		query: 'id eq 1',
		error: null,
		result: null
	}
})
</script>
</body>