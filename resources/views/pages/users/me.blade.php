@extends('base')

@section("title", "My Account")

@section("main")
<div>
	<h2>My Account</h2>
	<table>
		<tr>
			<th>Name</th>
			<td>{{ $user['name'] }}</td>
		</tr>
		<tr>
			<th>Email</th>
			<td>{{ $user['email'] }}</td>
		</tr>
		<tr>
			<th>Joined</th>
			<td>{{ $user['created_at'] }}</td>
		</tr>
	</table>
</div>
@endsection