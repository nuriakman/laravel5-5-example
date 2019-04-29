<?php

use App\Models\Post;

describe( 'User', function () {

	beforeAll( function () {

		$this->laravel->artisan( 'migrate' );

	} );

	using( [ 'database transactions' ], function () {

		given( 'user', function () {
			return factory( App\Models\User::class )->create();
		} );

		given( 'post', function () {
			return factory( App\Models\Post::class )->create(
				[

					'title'     => '111',
					'slug'      => '111',
					'seo_title' => '111',
					'user_id'   => 20,
					'image'     => '/files/user2/img08.jpg',

				]
			);
		} );


		it( 'should delete posts when a user is deleted', function () {

			$nbPostsIni = Post::count();

			//$user = $this->user; // Create an user
			//$this->user->posts()->create( [
			//	                              'title'     => '111',
			//	                              'slug'      => '111',
			//	                              'seo_title' => '111',
			//	                              'user_id'   => 20,
			//	                              'image'     => '/files/user2/img08.jpg',
			//                              ] );

			expect( Post::count() )->toBe( $nbPostsIni );

		} );

	} );

} );
