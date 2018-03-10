<?php

describe( 'User', function () {

	given( 'user', function () {
		return factory( App\Models\User::class )->create();
	} );

	it( 'should delete posts when a user is deleted', function () {

		//$user = $this->user; // Create an user

	} );

} );
