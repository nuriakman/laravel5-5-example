<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
|--------------------------------------------------------------------------
| Frontend
|--------------------------------------------------------------------------|
*/

Route::get( 'photo', 'PhotoController@create' )->middleware( 'auth' );
Route::post( 'photo', 'PhotoController@store' );

Route::get( '/tc', function () {
	return new App\Mail\Contact( [
		                             'nom'     => 'Durand',
		                             'email'   => 'durand@chezlui.com',
		                             'message' => 'Je voulais vous dire que votre site est magnifique !'
	                             ] );
} );

Route::get( 't', function () {
	return redirect()->route( 'test' );
} );

Route::get( 'w/{n?}', 'WelcomeController@index' )->where( 'n', '[0-9]+' )->name( 'welcome' );

Route::get( 'test', function () {

	$oColl = new stdClass();

	$oColl                 = (object) [ ];
	$oColl->clef1          = (object) [ ];
	$oColl->clef1->id      = 'clé1';
	$oColl->clef1->couleur = 'rouge';

	$oColl->clef2          = (object) [ ];
	$oColl->clef2->id      = 'clé2';
	$oColl->clef2->couleur = 'vert';

	// dd($oColl);

//$oVal->key1->var1 = "something"; // the warning is ignored thanks to @
//$oVal->key1->var2 = "something else";


	return view( 'front.test', [ 'oColl' => $oColl ] );
	// ->withNumero(57)->withParent('Pierre');
} )->name( 'test' );

Route::get( 'article/{n}', function ( $n ) {
	return view( 'article' )->with( 'numero', $n );
} )->where( 'n', '[0-9]+' );


Route::get( 'users', 'UsersController@create' );
Route::post( 'users', 'UsersController@store' );


// Home
Route::name( 'home' )->get( '/', 'Front\PostController@index' );


// Contact
// Route::resource('contacts', 'Front\ContactController', ['only' => ['create', 'store']]);

Route::name( 'contacts.create' )->get( 'contacts', 'Front\ContactController@create' );
Route::name( 'contacts.store' )->post( 'contacts', 'Front\ContactController@store' );


// Posts and comments
Route::prefix( 'posts' )->namespace( 'Front' )->group( function () {
	Route::name( 'posts.display' )->get( '{slug}', 'PostController@show' );
	Route::name( 'posts.tag' )->get( 'tag/{tag}', 'PostController@tag' );
	Route::name( 'posts.search' )->get( '', 'PostController@search' );
	Route::name( 'posts.comments.store' )->post( '{post}/comments', 'CommentController@store' );
	Route::name( 'posts.comments.comments.store' )
	     ->post( '{post}/comments/{comment}/comments', 'CommentController@store' );
	Route::name( 'posts.comments' )->get( '{post}/comments/{page}', 'CommentController@comments' );
} );

Route::resource( 'comments', 'Front\CommentController', [
	'only'  => [ 'update', 'destroy' ],
	'names' => [ 'destroy' => 'front.comments.destroy' ]
] );

Route::name( 'category' )->get( 'category/{category}', 'Front\PostController@category' );

// Authentification
Auth::routes();


/*
|--------------------------------------------------------------------------
| Backend
|--------------------------------------------------------------------------|
*/

Route::prefix( 'admin' )->namespace( 'Back' )->group( function () {

	Route::middleware( 'redac' )->group( function () {

		Route::name( 'admin' )->get( '/', 'AdminController@index' );

		// Posts
		Route::name( 'posts.seen' )->put( 'posts/seen/{post}', 'PostController@updateSeen' )
		     ->middleware( 'can:manage,post' );
		Route::name( 'posts.active' )->put( 'posts/active/{post}/{status?}', 'PostController@updateActive' )
		     ->middleware( 'can:manage,post' );
		Route::resource( 'posts', 'PostController' );

		// Notifications
		Route::name( 'notifications.index' )->get( 'notifications/{user}', 'NotificationController@index' );
		Route::name( 'notifications.update' )->put( 'notifications/{notification}', 'NotificationController@update' );

		// Medias
		Route::view( 'medias', 'back.medias' )->name( 'medias.index' );

	} );

	Route::middleware( 'admin' )->group( function () {

		// Users
		Route::name( 'users.seen' )->put( 'users/seen/{user}', 'UserController@updateSeen' );
		Route::name( 'users.valid' )->put( 'users/valid/{user}', 'UserController@updateValid' );
		Route::resource( 'users', 'UserController', [ 'only' => [
			'index',
			'edit',
			'update',
			'destroy'
		]
		] );

		// Categories
		Route::resource( 'categories', 'CategoryController', [ 'except' => 'show' ] );

		// Contacts
		Route::name( 'contacts.seen' )->put( 'contacts/seen/{contact}', 'ContactController@updateSeen' );
		Route::resource( 'contacts', 'ContactController', [ 'only' => [
			'index',
			'destroy'
		]
		] );

		// Comments
		Route::name( 'comments.seen' )->put( 'comments/seen/{comment}', 'CommentController@updateSeen' );
		Route::resource( 'comments', 'CommentController', [ 'only' => [
			'index',
			'destroy'
		]
		] );

		// Settings
		Route::name( 'settings.edit' )->get( 'settings', 'AdminController@settingsEdit' );
		Route::name( 'settings.update' )->put( 'settings', 'AdminController@settingsUpdate' );

	} );

} );
